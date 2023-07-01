@extends('layouts.frontend.app')
@section('content')
    <section class="ftco-section ftco-cart">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <div class="cart-list">
                        <table class="table">
                            <thead class="thead-primary">
                                <tr class="text-center">
                                    <th></th>
                                    <th>&nbsp;</th>
                                    <th>Faktur</th>
                                    <th>Total</th>
                                    <th>Status Pembayaran</th>
                                    <th>Status Pesanan</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form class="billing-form" id="pendingPay">
                                    @foreach ($order as $item)
                                        <tr class="text-center">
                                            <td class="price"><a href="javascript:void(0)" id="orderdetails"
                                                    class="btn btn-primary py-3 px-4"
                                                    data-url="{{ route('order.detail', $item->id) }}"
                                                    value="{{ $item->id }}">Detail</a>
                                            </td>

                                            @if ($item->transaction_status == 'pending' && $item->order_status == 'P')
                                                <td class="product-remove"><a href="javascript:void(0)" id="cancel"
                                                        data-url="{{ route('order.cancel', $item->id) }}"><span
                                                            class="ion-ios-close"></span> Batal</a></td>
                                            @else
                                                <td>&nbsp;</td>
                                            @endif

                                            <td class="price">#{{ \Crypt::decrypt($item->invoice) }}</td>
                                            <td class="price">{{ rupiah($item->total) }}</td>
                                            <td class="total">
                                                @if ($item->transaction_status == 'settlement')
                                                    <h5> <span class="badge badge-success">Sudah dibayar</span> </h5>
                                                @elseif ($item->transaction_status == 'pending')
                                                    <h5> <span class="badge badge-warning">Belum dibayar</span> </h5>
                                                @else
                                                    <h5>
                                                        <span class="badge badge-danger">Gagal</span>
                                                    </h5>
                                                @endif
                                            </td>
                                            <td class="price">
                                                @if ($item->order_status == 'A')
                                                    <h5> <span class="badge badge-success">
                                                            <ion-icon name="shield-checkmark"></ion-icon> Diterima
                                                        </span> </h5>
                                                @elseif ($item->order_status == 'O')
                                                    <h5> <span class="badge badge-info">
                                                            <ion-icon name="bus"></ion-icon> Dalam pengiriman
                                                        </span> </h5>
                                                @elseif ($item->order_status == 'P' && $item->transaction_status == 'settlement')
                                                    <h5> <span class="badge badge-primary">
                                                            <ion-icon name="cube"></ion-icon> Dikemas
                                                        </span> </h5>
                                                @elseif ($item->order_status == 'P')
                                                    <h5> <span class="badge badge-warning">
                                                            <ion-icon name="refresh-outline"></ion-icon> Diproses
                                                        </span> </h5>
                                                @else
                                                    <h5>
                                                        <span class="badge badge-danger">
                                                            <ion-icon name="trash-bin"></ion-icon> Dibatalkan
                                                        </span>
                                                    </h5>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->transaction_status == 'pending' && $item->order_status == 'P')
                                                    <input type="hidden" id="orderId" value="{{ $item->id }}">
                                                    <button type="submit" class="btn btn-danger py-3 px-4">Bayar</button>
                                                @elseif($item->transaction_status == 'settlement' && $item->order_status == 'O')
                                                    <a href="{{ route('order.update', $item->id) }}"
                                                        class="btn btn-info py-3 px-4">Terima</a>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="detailOrder" tabindex="-1" role="dialog" aria-labelledby="detailLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailLabel">Detail Pesanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="align-content-center text-center align-items-center">
                        <h4 id="invoice"></h4>
                        <div id="product"></div>
                        <h5>Rp. <span id="total"></span></h5>
                        <hr>
                        <h5>Penerima</h5>
                        <div id="recipient" class="row">
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('frontend') }}/script/order.js"></script>
@endpush
