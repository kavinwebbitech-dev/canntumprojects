@extends('frontend.layouts.app')
@section('content')
    <!--New Address Modal -->

    <style>
        .delete-icon i {
            font-size: 18px;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .delete-icon:hover i {
            transform: scale(1.2);
            color: red;
        }
    </style>
    <div class="modal fade form_modal" id="newAddressModal" tabindex="-1" aria-labelledby="newAddressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="exampleModalLabel">Add Address</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row gy-4 gx-3" action="{{ route('user.addNewAddress') }}" method="post" id="myForm">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label">First Name*</label>
                            <input type="text" class="form-control" placeholder="Tom" name="fname" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name*</label>
                            <input type="text" class="form-control" placeholder="Latham" name="lname" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone*</label>
                            <input type="text" class="form-control" placeholder="Enter Your Phone" name="phone"
                                maxlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email*</label>
                            <input type="email" class="form-control" placeholder="tom@mail.com" name="email" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address*</label>
                            <input type="text" class="form-control" placeholder="Enter your address" name="address"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country*</label>
                            <select name="country_display" class="form-control" disabled>
                                <option selected>India</option>
                            </select>

                            <input type="hidden" name="country" id="country" value="India">
                        </div>

                        <div class="col-md-6 d-flex gap-1">
                            <div class="col-md-9">
                                <label class="form-label">State*</label>
                                <select name="state" id="state" class="form-control" required>
                                    <option value="">Select State</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">State Code</label>
                                <input type="text" id="state_code" name="state_code" class="form-control" readonly>
                            </div>
                        </div>



                        <div class="col-md-6">
                            <label class="form-label">Town / City*</label>
                            <select name="city" id="city" class="form-control" required>
                                <option value="">Select City</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pincode*</label>
                            <input type="text" class="form-control" placeholder="Enter Your Pincode" name="pincode"
                                maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required>
                            <small class="text-danger d-none" id="pincodeError">
                                Pincode must be exactly 6 digits
                            </small>
                        </div>
                        <script>
                            document.querySelector('input[name="pincode"]').addEventListener('input', function() {
                                this.value = this.value
                                    .replace(/\D/g, '') // remove non-digits
                                    .replace(/^0+/, '') // prevent starting with 0
                                    .slice(0, 6); // max 6 digits
                            });
                        </script>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="make_default"
                                    id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">Make this my default address</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn common_btn w-100 d-block">Save Address</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <section class="my_profile">
        <div class="container">
            <div class="row gx-2">
                @include('frontend.user.sidebar')
                <div class="col-lg-10">
                    <div class="profile_right">
                        <div class="row gy-4 align-items-center">
                            <div class="col-lg-4 col-md-6">
                                <h2 class="profile_title" style="margin-bottom: -20px !important">My Address</h2>
                            </div>
                            <div class="col-lg-8 col-md-6">
                                <p class="text-end"><a href="#" data-bs-toggle="modal"
                                        data-bs-target="#newAddressModal" class="btn common_btn"><i
                                            class="bi bi-plus-lg"></i>Add
                                        Address</a></p>
                            </div>
                        </div>
                        <br>
                        @if (session('addsuccess'))
                            <div id="successMessage" class="alert alert-success">
                                {{ session('addsuccess') }}
                            </div>

                            <script>
                                setTimeout(function() {
                                    document.getElementById('successMessage').style.display = 'none';
                                }, 5000);
                            </script>
                        @endif

                        @if (session('error'))
                            <div id="errorMessage" class="alert alert-danger">
                                {{ session('error') }}
                            </div>

                            <script>
                                setTimeout(function() {
                                    document.getElementById('errorMessage').style.display = 'none';
                                }, 5000);
                            </script>
                        @endif

                        <div class="my_address">
                            <div class="row gy-4">
                                @foreach ($userAddress as $address)
                                    <div class="col-lg-6">
                                        <div class="address_card">
                                            <input id="address1" class="radio-button" type="radio" name="radio"
                                                {{ $address->make_default == 1 ? 'checked' : '' }} />
                                            <div class="address_tail">
                                                <div class="row gx-3">
                                                    <div class="col-md-12">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h5 class="address_title">{{ $address->shipping_name }}</h5>
                                                            <p class="">
                                                                <a href="{{ route('user.address.delete', $address->id) }}"
                                                                    class="change_link ms-3 delete-icon">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </a>
                                                            </p>
                                                        </div>
                                                        <address class="address_text">{{ $address->shipping_address }},<br>
                                                            {{ $address->city ?? '' }} -
                                                            {{ $address->pincode }}
                                                        </address>
                                                        <p class="address_text">{{ $address->shipping_email }}</p>
                                                        <p class="address_text">{{ $address->shipping_phone }}</p>


                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- <script>
        const countryApi = "https://countriesnow.space/api/v0.1/countries/states";
        const cityApi = "https://countriesnow.space/api/v0.1/countries/state/cities";

        const country = document.getElementById("country");
        const state = document.getElementById("state");
        const city = document.getElementById("city");

        // Function to remove accents/diacritics
        function removeDiacritics(str) {
            return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        }

        // Load states when country changes
        country.addEventListener("change", async function() {
            state.innerHTML = '<option value="">Select State</option>';
            city.innerHTML = '<option value="">Select City</option>';
            state.disabled = true;
            city.disabled = true;

            if (!this.value) return;

            try {
                const res = await fetch(countryApi);
                const data = await res.json();

                const selectedCountry = data.data.find(c => c.name === this.value);

                if (selectedCountry && selectedCountry.states.length) {
                    selectedCountry.states.forEach(s => {
                        const opt = document.createElement("option");
                        opt.value = s.name;
                        opt.textContent = s.name;
                        state.appendChild(opt);
                    });
                    state.disabled = false;
                }
            } catch (e) {
                console.error("State load error", e);
            }
        });

        // Load cities when state changes
        state.addEventListener("change", async function() {
            city.innerHTML = '<option value="">Select City</option>';
            city.disabled = true;

            if (!this.value) return;

            try {
                const res = await fetch(cityApi, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        country: country.value,
                        state: this.value
                    })
                });

                const data = await res.json();

                if (data.data && data.data.length) {
                    data.data.forEach(c => {
                        // Clean city name
                        const cleanCity = removeDiacritics(c);

                        const opt = document.createElement("option");
                        opt.value = cleanCity;
                        opt.textContent = cleanCity;
                        city.appendChild(opt);
                    });
                    city.disabled = false;
                }
            } catch (e) {
                console.error("City load error", e);
            }
        });
    </script> --}}

    <script>
        const countryApi = "https://countriesnow.space/api/v0.1/countries/states";
        const cityApi = "https://countriesnow.space/api/v0.1/countries/state/cities";

        const country = document.getElementById("country");
        const state = document.getElementById("state");
        const city = document.getElementById("city");
        const stateCodeField = document.getElementById("state_code");

        /* ================= GST STATE CODE MAP ================= */
        const gstStateCodes = {
            "Jammu and Kashmir": "01",
            "Himachal Pradesh": "02",
            "Punjab": "03",
            "Chandigarh": "04",
            "Uttarakhand": "05",
            "Haryana": "06",
            "Delhi": "07",
            "Rajasthan": "08",
            "Uttar Pradesh": "09",
            "Bihar": "10",
            "Sikkim": "11",
            "Arunachal Pradesh": "12",
            "Nagaland": "13",
            "Manipur": "14",
            "Mizoram": "15",
            "Tripura": "16",
            "Meghalaya": "17",
            "Assam": "18",
            "West Bengal": "19",
            "Jharkhand": "20",
            "Odisha": "21",
            "Chhattisgarh": "22",
            "Madhya Pradesh": "23",
            "Gujarat": "24",
            "Dadra and Nagar Haveli and Daman and Diu": "26",
            "Maharashtra": "27",
            "Karnataka": "29",
            "Goa": "30",
            "Lakshadweep": "31",
            "Kerala": "32",
            "Tamil Nadu": "33",
            "Puducherry": "34",
            "Andaman and Nicobar Islands": "35",
            "Telangana": "36",
            "Andhra Pradesh": "37",
            "Ladakh": "38"
        };

        /* ============== Remove Diacritics ============== */
        function removeDiacritics(str) {
            return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        }

        /* ============== LOAD STATES ============== */
        country.addEventListener("change", async function() {

            state.innerHTML = '<option value="">Select State</option>';
            city.innerHTML = '<option value="">Select City</option>';
            stateCodeField.value = "";

            state.disabled = true;
            city.disabled = true;

            if (!this.value) return;

            try {
                const res = await fetch(countryApi);
                const data = await res.json();

                const selectedCountry = data.data.find(c => c.name === "India");

                if (selectedCountry && selectedCountry.states.length) {
                    selectedCountry.states.forEach(s => {
                        const opt = document.createElement("option");
                        opt.value = s.name;
                        opt.textContent = s.name;
                        state.appendChild(opt);
                    });
                    state.disabled = false;
                }
            } catch (e) {
                console.error("State load error", e);
            }
        });

        /* ============== LOAD CITIES + AUTO STATE CODE ============== */
        state.addEventListener("change", async function() {

            city.innerHTML = '<option value="">Select City</option>';
            city.disabled = true;

            // Auto fill GST state code
            if (gstStateCodes[this.value]) {
                stateCodeField.value = gstStateCodes[this.value];
            } else {
                stateCodeField.value = "";
            }

            if (!this.value) return;

            try {
                const res = await fetch(cityApi, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        country: country.value,
                        state: this.value
                    })
                });

                const data = await res.json();

                if (data.data && data.data.length) {
                    data.data.forEach(c => {
                        const cleanCity = removeDiacritics(c);

                        const opt = document.createElement("option");
                        opt.value = cleanCity;
                        opt.textContent = cleanCity;
                        city.appendChild(opt);
                    });
                    city.disabled = false;
                }
            } catch (e) {
                console.error("City load error", e);
            }
        });

        window.addEventListener('load', function() {
            const event = new Event('change');
            document.getElementById("country").dispatchEvent(event);
        });
    </script>
@endsection
