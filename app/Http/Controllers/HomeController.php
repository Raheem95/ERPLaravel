<?php

namespace App\Http\Controllers;

use App\Account;
use App\Item;
use App\Sale;
use App\SaleDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $TopItemsSale = SaleDetails::with("item")
            ->selectRaw('sum(ItemQTY) as SaleQTY, ItemID')
            ->groupBy('ItemID')
            ->orderBy('SaleQTY', 'desc')
            ->take(10)
            ->get();
        $TopItemsSaleLabels = [];
        $TopItemsSaleData = [];
        foreach ($TopItemsSale as $Item) {
            $TopItemsSaleLabels[] = $Item->item->ItemName;
            $TopItemsSaleData[] = $Item->SaleQTY;
        }
        $TopItemsIncome = SaleDetails::with("item")
            ->selectRaw('sum(ItemQTY*ItemPrice) as IncomeQTY, ItemID')
            ->groupBy('ItemID')
            ->orderBy('IncomeQTY', 'desc')
            ->take(10)
            ->get();


        $TopItemsIncomeLabels = [];
        $TopItemsIncomeData = [];

        foreach ($TopItemsIncome as $Item) {

            $TopItemsIncomeLabels[] = $Item->item->ItemName;
            $TopItemsIncomeData[] = $Item->IncomeQTY;
        }
        $Sales = DB::table('sale')
            ->join('monthes', DB::raw('MONTH(sale.created_at)'), '=', 'monthes.MonthID')
            ->select(DB::raw('MONTH(sale.created_at) as month'), 'monthes.Monthname', DB::raw('SUM(sale.TotalSale) as total_sales'))
            ->groupBy('month', 'monthes.Monthname')
            ->get();
        $SalesLabels = [];
        $SalesData = [];

        foreach ($Sales as $Sale) {

            $SalesLabels[] = $Sale->Monthname;
            $SalesData[] = $Sale->total_sales;
        }

        $CustomerSale = Sale::with("customer")
            ->selectRaw('sum(TotalSale) as TotalSale, CustomerID')
            ->groupBy('CustomerID')
            ->take(10)
            ->get();
        $CustomerSaleLabels = [];
        $CustomerSaleData = [];

        foreach ($CustomerSale as $Sale) {

            $CustomerSaleLabels[] = $Sale->customer->CustomerName;
            $CustomerSaleData[] = $Sale->TotalSale;
        }
        $CustomerDepit = Account::where("AccountTypeID", "5")
            ->where("lastChildNum", "0")
            ->where("lastChildNum", "0")
            ->take(10)
            ->get();
        $CustomerDepitLabels = [];
        $CustomerDepitData = [];

        foreach ($CustomerDepit as $Account) {

            $CustomerDepitLabels[] = $Account->AccountName;
            $CustomerDepitData[] = $Account->Balance;
        }
        return view('home')->with([
            "TopItemsSaleLabels" => $TopItemsSaleLabels,
            "TopItemsSaleData" => $TopItemsSaleData,
            "TopItemsIncomeLabels" => $TopItemsIncomeLabels,
            "TopItemsIncomeData" => $TopItemsIncomeData,
            "SalesLabels" => $SalesLabels,
            "SalesData" => $SalesData,
            "CustomerSaleLabels" => $CustomerSaleLabels,
            "CustomerSaleData" => $CustomerSaleData,
            "CustomerDepitLabels" => $CustomerDepitLabels,
            "CustomerDepitData" => $CustomerDepitData,
        ]);
    }
}
