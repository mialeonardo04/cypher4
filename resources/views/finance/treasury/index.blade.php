@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Treasury</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#addDeposit"><i class="fa fa-money-bill"></i>Add Deposit</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add Bank</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            {{--            <h5><span class="span">This page contains a list of Travel Order which has been formed.</span></h5>--}}
            <table class="table display">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-left">Deposit Source</th>
                    <th class="text-center">Credit</th>
                    <th class="text-center">Debit</th>
                    <th class="text-center">Current Balance</th>
                    <th class="text-center">Available Balance</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($treasuries as $key => $treasure)
                        <tr>
                            <td align="center">{{$key + 1}}</td>
                            <td>
                                <a href="{{URL::route('treasury.view', base64_encode(rand(100, 999)."-".$treasure->id))}}" class="text-hover-danger">
                                    {{strtoupper("[".$treasure->currency."] ".$treasure->source)}}&nbsp;<a href="{{URL::route('treasury.history', base64_encode(rand(100, 999)."-".$treasure->id))}}" class="btn btn-icon btn-xs btn-primary"><i class="fa fa-history"></i></a>
                                </a>
                                @if(!empty($tre_his[$treasure->id]))
                                    <label class="badge badge-warning text-white">{{count($tre_his[$treasure->id])}}</label>
                                @endif
                            </td>
                            <td align="center">
                                <label class="text-success">
                                    {{(empty($cashIn[$treasure->id])) ? number_format(0, 2) : number_format(array_sum($cashIn[$treasure->id]), 2)}}
                                </label>
                            </td>
                            <td align="center">
                                <label class="text-danger">
                                    {{(empty($cashOut[$treasure->id])) ? number_format(0, 2) : number_format(str_replace("-", "", array_sum($cashOut[$treasure->id])), 2)}}
                                </label>
                            </td>
                            <td align="center">
                                <label class="text-success">
                                    {{(empty($cashSum[$treasure->id])) ? number_format(0, 2) : number_format(array_sum($cashSum[$treasure->id]), 2)}}
                                </label>
                            </td>
                            <td align="center">
                                <label class="text-success">
                                    {{(empty($cashSum[$treasure->id])) ? number_format(0, 2) : number_format(array_sum($cashSum[$treasure->id]) - $treasure->actual_idr, 2)}}
                                </label>
                            </td>
                            <td align="center">
                                <button class="btn btn-icon btn-xs btn-success" data-toggle="modal" data-target="#editItem" onclick="button_edit('{{base64_encode(rand(100, 999)."-".$treasure->id)}}')"><i class="fa fa-pen"></i></button>
                                <button class="btn btn-icon btn-xs btn-danger" onclick="button_reject('{{base64_encode(rand(100, 999)."-".$treasure->id)}}')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Bank</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('treasury.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Bank Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Item Name" name="bank_name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Branch Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Branch Name" name="branch_name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Account Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Account Name" name="account_name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Account Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Account Number" name="account_number" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Currency</label>
                            <div class="col-md-9">
                                <select name="currency" class="form-control select2" required>
                                    @foreach(json_decode($list_currency) as $key => $value)
                                        <option value="{{$key}}" {{($key == "IDR") ? "selected" : ""}}>{{strtoupper($key."-".$value)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if($accounting_mode == 1)
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">COA</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control autocomplete" placeholder="COA" name="coa" id="coa">
                                </div>
                                <div id="coa-target"></div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addDeposit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Bank</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('treasury.deposit')}}" id="form-deposit">
                    @csrf
                    <div class="modal-body">
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Date</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" readonly placeholder="Item Name" id="kt_datepicker_3" name="date_input" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Storage Bank</label>
                            <div class="col-md-9">
                                <select name="source" class="form-control select2" required>
                                    <option value="">Select Source</option>
                                    @if(count($treasuries) > 0)
                                        @foreach($treasuries as $treasure)
                                            <option value="{{$treasure->id}}">{{strtoupper("[".$treasure->currency."] ".$treasure->source)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Project</label>
                            <div class="col-md-9">
                                <select name="project" class="form-control select2" required>
                                    <option value="">Select Project</option>
                                    @if(count($projects) > 0)
                                        @foreach($projects as $project)
                                            <option value="{{$project->id}}">{{strtoupper($project->prj_name)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" class="form-control" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Amount</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" placeholder="Amount" name="amount" required>
                            </div>
                        </div>
                        <div class="alert alert-danger" role="alert">
                            This transaction request will need approval before it will appear on the treasury records.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-deposit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                        <button type="submit" id="btn-submit"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editItem" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Bank</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('treasury.edit')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Bank Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Item Name" id="bank_name" name="bank_name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Branch Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Branch Name" id="branch_name" name="branch_name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Account Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Account Name" id="account_name" name="account_name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Account Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Account Number" id="account_number" name="account_number" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Currency</label>
                            <div class="col-md-9">
                                <select name="currency" class="form-control select2" id="currency" required>
                                    @foreach(json_decode($list_currency) as $key => $value)
                                        <option value="{{$key}}">{{strtoupper($key."-".$value)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if($accounting_mode == 1)
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right">COA</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control autocomplete" placeholder="COA" name="coa" id="coa-edit">
                                </div>
                                <div id="coa-target-edit"></div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="id_tre" name="id_tre">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="button" onclick="submit_edit_form()" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                        <button type="submit" id="btn-submit-edit" name="submit" class="btn btn-primary font-weight-bold"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/typeahead.js?v=7.0.5')}}"></script>
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet">
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script>
        function submit_edit_form(){
            Swal.fire({
                title: "Update",
                text: "Are you sure you want to update this data?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $("#btn-submit-edit").click()
                }
            })
        }
        function button_reject(x){
            Swal.fire({
                title: "Delete",
                text: "Are you sure you want to delete?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('treasury.delete')}}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            '_token' : '{{csrf_token()}}',
                            'val' : x
                        },
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }
        function button_edit(x){
            $.ajax({
                url: "{{URL::route('treasury.find')}}",
                type: "POST",
                dataType: "json",
                data: {
                    '_token' : '{{csrf_token()}}',
                    'val' : x
                },
                cache: false,
                success: function(response){
                    $("#bank_name").val(response.source)
                    $("#branch_name").val(response.branch)
                    $("#account_name").val(response.account_name)
                    $("#account_number").val(response.account_number)
                    $("#currency").val(response.currency).trigger('change')
                    $("#coa-edit").val(response.bank_code)
                    $("#id_tre").val(response.id)
                }
            })
        }
        $(document).ready(function(){
            $("#coa").autocomplete({
                source: "{{route('coa.get')}}",
                minLength: 1,
                appendTo: "#coa-target",
                select: function(event, ui){
                    $(this).val(ui.item.label)
                }
            })
            $("#coa-edit").autocomplete({
                source: "{{route('coa.get')}}",
                minLength: 1,
                appendTo: "#coa-target-edit",
                select: function(event, ui){
                    $(this).val(ui.item.label)
                }
            })
            $("#btn-submit-edit").hide()
            $("#btn-submit").hide()
            $("#btn-deposit").click(function(){
                Swal.fire({
                    title: "Add Deposit",
                    text: "Are you sure you want to submit this data?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Submit",
                    cancelButtonText: "Cancel",
                    reverseButtons: true,
                }).then(function(result){
                    if(result.value){
                        $("#btn-submit").click()
                    }
                })
            })

            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
            $("select.select2").select2({
                width: "100%"
            })
        })
    </script>
@endsection
