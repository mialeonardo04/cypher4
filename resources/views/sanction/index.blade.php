@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Sanction
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add Deduction</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th nowrap="nowrap" class="text-center">ID#</th>
                        <th nowrap="nowrap" style="width: 20%">Name</th>
                        <th nowrap="nowrap" class="text-center" style="width: 20%">Notes</th>
                        <th nowrap="nowrap" class="text-right">Sanction Amount</th>
                        <th nowrap="nowrap" class="text-center">Sanction Date</th>
                        <th nowrap="nowrap" class="text-center">BoD Approval</th>
                        <th nowrap="nowrap" class="text-center" data-priority=1>#</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($sanction as $key => $value)
                            <tr>
                                <td>{{($key+1)}}</td>
                                <td class="text-center">{{$value->sanctionID}}</td>
                                <td>{{$value->emp_name}}</td>
                                <td class="text-center">{{$value->notes}}</td>
                                <td class="text-right">{{number_format($value->sanction_amount,2)}}</td>
                                <td class="text-center">{{date('d F Y',strtotime($value->sanction_date))}}</td>
                                <td class="text-center">
                                    @if($value->approved_by == null)
                                        <form method="post" action="{{route('sanction.approve',['id' => $value->id])}}">
                                            @csrf
                                            <button type="submit" name="approve" value="1" class="btn btn-primary btn-xs" onclick="return confirm('Are you sure?')"><i class="fa fa-money-bill-wave"></i>&nbsp;&nbsp;Approve</button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-success btn-xs" readonly=""><i class="fa fa-check-circle"></i>&nbsp;&nbsp;Approved</button>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form method="post" action="{{route('sanction.delete',['id' => $value->id])}}">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-icon btn-default" onclick="return confirm('Hapus data?');">
                                            <i class="fa fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Deduction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('sanction.store')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-6">
                                <div class="form-group">
                                    <label>Choose Employee</label>
                                    <select class="form-control" id="emp_name" name="emp_name">
                                        @foreach($employees as $key => $value)
                                            <option value="{{$value->id}}">{{$value->emp_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Sanction Date</label>
                                    <input type="date" class="form-control" name="date" placeholder="Sanction Date" />
                                </div>

                                <div class="form-group">
                                    <label>Sanction Amount</label>
                                    <input type="number" class="form-control" name="amount" />
                                </div>
                            </div>
                            <div class="form col-md-6">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea class="form-control" name="notes"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {
            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        });
    </script>
@endsection
