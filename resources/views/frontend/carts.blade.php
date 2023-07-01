@extends('layouts.frontend.app')
@section('content')
    <section class="ftco-section ftco-cart">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <div class="cart-list">
                        <form action="{{ route('carts.update') }}" method="post" id="carts">
                            @csrf
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr class="text-center">
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($carts as $item)
                                        <tr class="text-center">
                                            <td class="product-remove"><a
                                                    href="{{ route('carts.delete', $item->id) }}"><span
                                                        class="ion-ios-close"></span></a></td>

                                            <td class="image-prod">
                                                <img class="img"
                                                    src="{{ asset('backend/products/' . $item->products->thumbnails) }}"></img>
                                            </td>

                                            <td class="product-name">
                                                <h3>{{ $item->products->product_name }}</h3>
                                                {{-- <p>{{ $item->products->product_descriptions }}</p> --}}
                                            </td>

                                            <td class="price">{{ rupiah($item->products->price) }}</td>

                                            <td class="quantity">
                                                <div class="input-group mb-3">
                                                    <input type="hidden" name="carts_id[]" value="{{ $item->id }}">
                                                    <input type="text" class="quantity form-control input-number"
                                                        value="{{ $item->quantity }}" name="carts_quantity[]" min="1"
                                                        max="100">
                                                </div>
                                            </td>

                                            <td class="total">{{ rupiah($item->products->price * $item->quantity) }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="row justify-content-end col-lg-12 mt-5">
                                <p><a href="javascript:void(0)" onclick="this.closest('form').submit();return false;"
                                        class="btn btn-primary py-3 px-4">Perbarui</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <hr>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 ftco-animate fadeInUp ftco-animated">
                        <form class="billing-form" id="checkout">
                            @csrf
                            <h3 class="mb-4 billing-heading">Detail Tagihan</h3>
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname">Nama Depan</label>
                                        <input type="text" class="form-control" name="firstname" id="firstname" required
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname">Nama Belakang</label>
                                        <input type="text" class="form-control" name="lastname" id="lastname" required
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="country">Negara</label>
                                        <div class="select-wrap">
                                            <input type="text" class="form-control" name="state" id="state"
                                                required placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="streetaddress">Alamat</label>
                                        <input type="text" class="form-control" name="street" id="street" required
                                            placeholder="House number and street name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="detailstreet"
                                            placeholder="Apartemen, Bangunan, Rumah, dll (optional)">
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="towncity">Kota / Kab</label>
                                        <input type="text" class="form-control" name="city" id="city" required
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="postcodezip">Kodepos *</label>
                                        <input type="text" class="form-control" name="postcode" id="postcode" required
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">No Hp</label>
                                        <input type="text" class="form-control" name="phone" id="phone"
                                            required placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emailaddress">Email</label>
                                        <input type="text" class="form-control" name="email" id="email"
                                            required placeholder="">
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <input type="hidden" name="total" id="totals" value="{{ $total }}">
                            </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="row mt-5 pt-3">
                            <div class="col-md-12 d-flex mb-5">
                                <div class="cart-detail cart-total p-3 p-md-4">
                                    <h3>Total Belanja</h3>
                                    <hr>
                                    <p class="d-flex total-price">
                                    <h5>Total</h5>
                                    <h6>{{ rupiah($total) }}</h6>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="cart-detail p-3 p-md-4">
                                    @if ($total != 0)
                                        <p><button type="submit" class="btn btn-primary py-3 px-4">Bayar</button></p>
                                        </p>
                                    @endif
                                    {{-- <p><a href="javascript:void(0)" class="btn btn-primary py-3 px-4"
                                            onclick="document.getElementById('checkout').submit();">Checkout</a>
                                    </p> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    </form><!-- END -->
                    <!-- .col-md-8 -->
                </div>
            </div>
        </div>
    </section>

    @push('js')
        <script src="{{ asset('frontend') }}/script/cart.js"></script>
    @endpush
@endsection
