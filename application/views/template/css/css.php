<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | DataTables</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/fontawesome-free/css/all.min.css')?>">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables-select/css/select.bootstrap4.css')?>" rel="stylesheet">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-3.1.0/dist/css/adminlte.min.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/toastr/toastr.min.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/izitoast/dist/css/iziToast.min.css')?>">
</head>

<style>
    /* .tableFixHead2 { overflow-y: auto; height: 250px; width : 1180px; } */
    th, td { white-space: nowrap; }
    table.table tr th {
        background-color: #4CAF50 !important;
        color           : white !important;
    }

    .table-condensed>thead>tr>th, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>tbody>tr>td, .table-condensed>tfoot>tr>td{
        padding-top   : 5px;
        padding-bottom: 0px;
        font-size     : 14px;
    }

    .modal-default {
        padding: 0 !important; 
    }

    .modal-default .modal-dialog_df {
        width  : 1200px;
        height : 300px;
        margin : 5px;
        padding: 0;
    }

    .modal-default .modal-content_df {
        height       : auto;
        min-height   : 300px;
        border       : 0 none;
        border-radius: 0;
        box-shadow   : none;
    }

    .customers_border {
        font-family    : "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }
    .customers_border td, .customers_border th {
        border   : 1px solid #ddd;
        padding  : 2px;
        font-size: 12px;
    }
    .customers_border tr:nth-child(even){background-color: #f2f2f2;}
    .customers_border tr:hover {background-color: #ddd;}
    .customers_border th {
        border          : 1px solid #ddd;
        padding-top     : 1px;
        padding-bottom  : 1px;
        text-align      : left;
        background-color: #4CAF50;
        color           : white;
    }

    .customers {
        font-family    : "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }
    .customers td, .customers th {
        border   : 0px solid #ddd;
        padding  : 2px;
        font-size: 12px;
    }
    .customers tr:nth-child(even){background-color: #f2f2f2;}
    .customers tr:hover {background-color: #ddd;}
    .customers th {
        border          : 0px solid #ddd;
        padding-top     : 1px;
        padding-bottom  : 1px;
        text-align      : left;
        background-color: #4CAF50;
        color           : white;
    }

</style>