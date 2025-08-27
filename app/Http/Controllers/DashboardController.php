<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Deal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date');
        $store_id = $request->get('store_id');
        $user_id = $request->get('user_id');

        $base = Deal::query()
            ->when($date, fn($q) => $q->whereDate('created_date', $date))
            ->when($store_id, fn($q) => $q->where('store_id', $store_id));

        // for now, just pass some dummy data to test
        $total_datas = [
            'mapping' => (clone $base)->where('stage', 'mapping')->count(),
            'visit' => (clone $base)->where('stage', 'visit')->count(),
            'quotation' => (clone $base)->where('stage', 'quotation')->count(),
            'won' => (clone $base)->where('stage', 'won')->count(),
            'lost' => (clone $base)->where('stage', 'lost')->count(),
        ];

        // === Category leader per deal ===
        $dealRows = (clone $base)->get();                         // deals after filters
        $dealIndex = $dealRows->keyBy('deals_id');                // quick lookup
        $dealIds = $dealRows->pluck('deals_id');

        if ($dealIds->isEmpty()) {
            $categoryBar = [
                'labels' => [],
                'deals' => [],
                'values' => [],
            ];
        } else {
            // Aggregate item values per deal per category
            $catAgg = DB::table('deals_items as di')
                ->join('items as i', 'i.item_no', '=', 'di.item_no')
                ->whereIn('di.deals_id', $dealIds)
                ->groupBy('di.deals_id', 'i.category')
                ->select([
                    'di.deals_id',
                    'i.category',
                    DB::raw('COALESCE(SUM(di.line_total), 0) as cat_value'),
                    DB::raw('COALESCE(SUM(di.quantity), 0)   as cat_qty'),
                ])
                ->get();

            // Pick dominant category for each deal (by cat_value; change to cat_qty if you want)
            $dominants = [];
            foreach ($catAgg as $r) {
                $did = $r->deals_id;
                if (!isset($dominants[$did]) || (float) $r->cat_value > (float) $dominants[$did]['cat_value']) {
                    $dominants[$did] = [
                        'category' => $r->category ?? 'Uncategorized',
                        'cat_value' => (float) $r->cat_value,
                    ];
                }
            }

            // Group by dominant category → count deals + sum of deal_size
            $byCat = [];
            foreach ($dominants as $dealId => $dom) {
                $cat = $dom['category'] ?: 'Uncategorized';
                $dealSize = (float) ($dealIndex[$dealId]->deal_size ?? 0);
                if (!isset($byCat[$cat])) {
                    $byCat[$cat] = ['deals' => 0, 'values' => 0.0];
                }
                $byCat[$cat]['deals'] += 1;
                $byCat[$cat]['values'] += $dealSize;
            }

            // Sort by deals desc (then value desc) and keep top N if desired
            uasort($byCat, function ($a, $b) {
                if ($a['deals'] === $b['deals']) {
                    return $b['values'] <=> $a['values'];
                }
                return $b['deals'] <=> $a['deals'];
            });
            // $byCat = array_slice($byCat, 0, 10, true); // uncomment to limit top 10

            $labels = array_keys($byCat);
            $deals = array_map(fn($v) => $v['deals'], array_values($byCat));
            $values = array_map(fn($v) => round($v['values'], 2), array_values($byCat));

            $categoryBar = compact('labels', 'deals', 'values');
        }

        // === 1) Salper: total points terbanyak ===
        // Assumes tables: points (deals_id, salper_id, total_points), salpers (salper_id, salper_name)
        $topSalpers = DB::table('points as p')
            ->join('deals as d', 'd.deals_id', '=', 'p.deals_id')
            ->leftJoin('salpers as s', 's.salper_id', '=', 'p.salper_id')
            ->when($date, fn($q) => $q->whereDate('d.created_date', $date))
            ->when($store_id, fn($q) => $q->where('d.store_id', $store_id))
            // ->when($user_id, fn($q) => $q->where('d.created_by', $user_id))
            ->groupBy('p.salper_id', 's.salper_name')
            ->selectRaw('p.salper_id, COALESCE(s.salper_name, CONCAT("Salper #", p.salper_id)) as salper_name')
            ->selectRaw('SUM(p.total_points) as total_points')
            ->selectRaw('COUNT(DISTINCT p.deals_id) as deals_count')
            ->orderByDesc('total_points')
            ->limit(10)
            ->get();

        // === 2) Value transaksi terbesar per deal ===
        $topDeals = (clone $base)
            ->select(['deals_id', 'deal_name', 'cust_name', 'store_name', 'deal_size'])
            ->orderByDesc('deal_size')
            ->limit(10)
            ->get();

        // === 3) Customer terbanyak melakukan transaksi ===
        $topCustomers = (clone $base)
            ->selectRaw('id_cust, COALESCE(cust_name, "Unknown") as cust_name')
            ->selectRaw('COUNT(*) as transaksi')
            ->selectRaw('COALESCE(SUM(deal_size), 0) as total_value')
            ->groupBy('id_cust', 'cust_name')
            ->orderByDesc('transaksi')
            ->limit(10)
            ->get();

        // --- Effectivity (guard division by zero) ---
        $visitTotal = (int) ($total_datas['visit'] ?? 0);
        $quotationTotal = (int) ($total_datas['quotation'] ?? 0);
        $wonTotal = (int) ($total_datas['won'] ?? 0);

        $effectivity = [
            'visit' => $visitTotal > 0 ? round(($wonTotal / $visitTotal) * 100, 2) : 0.0,
            'quotation' => $quotationTotal > 0 ? round(($wonTotal / $quotationTotal) * 100, 2) : 0.0,
        ];

        $lostReasons = (clone $base)
            ->where('stage', 'lost')
            ->selectRaw('COALESCE(NULLIF(lost_reason, ""), "Unknown") as reason, COUNT(*) as total')
            ->groupBy('reason')
            ->orderByDesc('total')
            ->get();

        $lostReasonChart = [
            'labels' => $lostReasons->pluck('reason'),
            'data' => $lostReasons->pluck('total'),
        ];

        $filters = [
            'date' => $date,
            'store_id' => $store_id,
            'user_id' => $user_id,
        ];

        return view('dashboard', compact(
            'total_datas',
            'categoryBar',
            'filters',
            'topSalpers',
            'topDeals',
            'topCustomers',
            'effectivity',
            'lostReasonChart'
        ));
    }
}
