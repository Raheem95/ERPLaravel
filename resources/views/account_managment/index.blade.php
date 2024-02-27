@extends('layouts.app')

@section('content')
    <div class = "row">
        <div class ='col-md-4'>
            <a href='AccountManagment/AccountTypes'>
                <b><label style='background:#0c2045;color:white;padding:10px;width:100%;text-align:center;'>انواع
                        الحسابات</label></b>
                <button class='btn'>
                    <img img width='100' height='100' src='img/accounts.png'><br>
                </button>
            </a>
        </div>
        <div class ='col-md-4'>
            <a href='AccountManagment/Currencies'>
                <b><label
                        style='background:#0c2045;color:white;padding:10px;width:100%;text-align:center;'>العملات</label></b>
                <button class='btn'>
                    <img img width='100' height='100' src='img/accounts.png'><br>
                </button>
            </a>
        </div>
        <div class ='col-md-4'>
            <a href='AccountManagment/Accounts'>
                <b><label
                        style='background:#0c2045;color:white;padding:10px;width:100%;text-align:center;'>إدارةالحسابات</label></b>
                <button class='btn'>
                    <img img width='100' height='100' src='img/accounts.png'><br>
                </button>
            </a>
        </div>
        <div class ='col-md-4'>
            <a href='dailyHome.php'>
                <b><label style='background:#0c2045;color:white;padding:10px;width:100%;text-align:center;'>قيود
                        اليومية</label></b>
                <button name='Categories' class='btn'>
                    <img img width='100' height='100' src='img/daily.png'>
                </button>
            </a>
        </div>
        <div class ='col-md-4'>
            <a href='Salaries.php'>
                <b><label
                        style='background:#0c2045;color:white;padding:10px;width:100%;text-align:center;'>المرتبات</label></b>
                <button name='Categories' class='btn'>
                    <img img width='100' height='100' src='img/salary.png'>
                </button>
            </a>
        </div>
        <div class ='col-md-4'>
            <a href='Loans.php'>
                <b><label
                        style='background:#0c2045;color:white;padding:10px;width:100%;text-align:center;'>سلفيات</label></b>
                <button name='Categories' class='btn'>
                    <img img width='100' height='100' src='img/loan.png'>
                </button>
            </a>
        </div>
        <div class ='col-md-4'>
            <a href='expenses.php'>
                <b><label
                        style='background:#0c2045;color:white;padding:10px;width:100%;text-align:center;'>منصرفات</label></b>
                <button name='Categories' class='btn'>
                    <img img width='100' height='100' src='img/expenses.png'>
                </button>
            </a>
        </div>
        <div class ='col-md-4'>
            <a href='AccountingReports.php'>
                <b><label
                        style='background:#0c2045;color:white;padding:10px;width:100%;text-align:center;'>التقارير</label></b>
                <button name='Categories' class='btn'>
                    <img img width='100' height='100' src='img/reports.png'>
                </button>
            </a>
        </div>
    </div>
@endsection
