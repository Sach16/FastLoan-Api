@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit user</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.users.update', $user->id) }}" class="form-horizontal"
          enctype="multipart/form-data" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <input type="hidden" name="id" value="{{$user->id}}">
        {{--Profile picture--}}
        <div class="form-group col-md-8 {{ hasErrors('profile_picture', $errors) }}">
            <label class="control-label" for="inputProfilePicture">Profile picture</label>
            <input type="file" class="form-control" id="inputProfilePicture" name="profile_picture">
            <span class="help-block">
                    <small>
                        <em>Only Image files with maximum 300 x 300 resolution </em>
                    </small>
            </span>
        </div>

        {{--First Name--}}
        <div class="form-group col-md-8 {{ hasErrors('first_name', $errors) }}">
            <label class="control-label" for="inputFirstName">First Name</label>
            <input type="text" class="form-control" id="inputFirstName" name="first_name" value="{{ $user->first_name }}">
        </div>

        {{--Last Name--}}
        <div class="form-group col-md-8 {{ hasErrors('last_name', $errors) }}">
            <label class="control-label" for="inputLastName">Last Name</label>
            <input type="text" class="form-control" id="inputLastName" name="last_name" value="{{ $user->last_name }}">
        </div>

        {{--Email--}}
        <div class="form-group col-md-8 {{ hasErrors('email', $errors) }}">
            <label class="control-label" for="inputEmail">Email ID</label>
            <input type="text" class="form-control" name="email" id="inputEmail" value="{{ $user->email }}" readonly>
        </div>

        {{--Phone--}}
        <div class="form-group col-md-8 {{ hasErrors('phone', $errors) }}">
            <label class="control-label" for="inputPhone">Phone Number</label>
            <input type="text" class="form-control" name="phone" id="inputPhone" value="{{ $user->phone }}" readonly>
        </div>

        {{--Gender--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.gender', $errors) }}">
            <label class="control-label" for="inputGender">Gender</label>
            <select name="settings[gender]" id="inputGender" class="form-control" data-chosen>
                <option selected disabled>Select gender</option>
                <option value="male" {{ isMatching('male', @$user->settings['gender']) }} >Male</option>
                <option value="female" {{ isMatching('female', @$user->settings['gender']) }} >Female</option>
                <option value="other" {{ isMatching('other', @$user->settings['gender']) }}>Other</option>
            </select>
        </div>

        {{--Designation--}}
        <div class="form-group col-md-8 {{ hasErrors('phone', $errors) }}">
            <label class="control-label" for="inputDesignation">Designation</label>
            <select name="designation_id" id="inputDesignation" class="form-control" data-chosen>
                <option selected disabled>Select designation</option>
                @foreach($designations as $designation)
                    <option value="{{ $designation->id }}" {{ isMatching($designation->id, $user->designation->id) }}>
                        {{ $designation->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{--Date of Birth--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.dob', $errors) }}">
            <label class="control-label" for="inputDateOfBirth">Date of Birth</label>
            <input type="text" class="form-control" data-dob-date
                   id="inputDateOfBirth" name="settings[dob]" value="{{ date('d-m-Y',strtotime($user->settings['dob'])) }}">
        </div>


        {{--Aplha Street--}}
        <div class="form-group col-md-10 {{ hasErrors('alphaStreet', $errors) }}">
            <label class="control-label" for="inputAlphaStreet">Street #1</label>
            <input type="text" class="form-control" id="inputAlphaStreet" name="alphaStreet"
                   value="{{ $user->addresses()->first()->alpha_street }}">
        </div>

        {{--Beta Street--}}
        <div class="form-group col-md-10 {{ hasErrors('betaStreet', $errors) }}">
            <label class="control-label" for="inputBetaStreet">Street #2</label>
            <input type="text" class="form-control" id="inputBetaStreet" name="betaStreet"
                   value="{{ $user->addresses()->first()->beta_street }}">
        </div>

        {{--City--}}
        <div class="form-group col-md-8 {{ hasErrors('city_id', $errors) }}">
            <label class="control-label" for="inputCity">City</label>
            <input type="hidden" value="{{ $user->addresses()->first()->city->id }}" id="selected-city">
            <select name="city_id" id="inputCity" class="form-control" data-city>
            </select>
        </div>

        {{--State--}}
        <div class="form-group col-md-8 {{ hasErrors('state', $errors) }}">
            <label class="control-label" for="inputState">State</label>
            <input type="text" class="form-control" id="inputState" name="state"
                   value="{{ $user->addresses()->first()->state }}">
        </div>

        {{--Country--}}
        <div class="form-group col-md-8 {{ hasErrors('country', $errors) }}">
            <label class="control-label" for="inputCountry">Country</label>
            <input type="text" class="form-control" id="inputCountry" name="country"
                   value="{{ $user->addresses()->first()->country }}">
        </div>

        {{--Zipcode--}}
        <div class="form-group col-md-6 {{ hasErrors('zip', $errors) }}">
            <label class="control-label" for="inputZipCode">Pincode</label>
            <input type="text" class="form-control" id="inputZipCode" name="zipcode"
                   value="{{ $user->addresses()->first()->zip }}">
        </div>

        {{--Assigned to Bank--}}
{{--         <div class="form-group col-md-8 {{ hasErrors('bank', $errors) }}">
            <label class="control-label" for="inputBank">Bank</label>
            <select name="bank" id="inputBank" class="form-control" data-chosen data-city-bank>
                <option value="{{ $user->banks()->first()->pivot->bank_id }}" selected>
                    {{ $user->banks()->first()->name }}
                </option>
            </select>
        </div> --}}

        {{--Joined as--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.joined_as', $errors) }}">
            <label class="control-label" for="inputDateOfJoin">Joined As</label>
            <input type="text" class="form-control"
                   id="inputDateOfJoin" name="settings[joined_as]" value="{{ @$user->settings['joined_as'] }}">
        </div>

        {{--Date Of Joining--}}
        <div class="form-group col-md-8 {{ hasErrors('DOJ', $errors) }}">
            <label class="control-label" for="inputDateOfJoin">Date of Join</label>
            <input type="text" class="form-control" data-dob-date
                   id="inputDateOfJoin" name="settings[DOJ]" value="{{ date('d-m-Y',strtotime($user->settings['DOJ'])) }}">
        </div>

        {{--Experience on Date Of Joining--}}
        <div class="form-group col-md-8 {{ hasErrors('exp_on_DOJ', $errors) }}">
            <label class="control-label" for="inputExpDateOfJoin">Experience on Date of Join (Years)</label>
            <input type="text" class="form-control"
                   id="inputExpDateOfJoin" name="settings[exp_on_DOJ]" value="{{ $user->settings['exp_on_DOJ'] }}">
        </div>


        {{--Marital Status--}}
        <div class="form-group col-md-8 {{ hasErrors('marital_status', $errors) }}">
            <label class="control-label" for="inputMaritalStatus">Marital Status</label>
            <select name="settings[marital_status]" id="inputMaritalStatus" class="form-control" data-chosen>
                <option disabled selected>Select marital status</option>
                <option value="Married" {{ isMatching('Married', @$user->settings['marital_status']) }}>Married</option>
                <option value="Unmarried" {{ isMatching('Unmarried', @$user->settings['marital_status']) }}>Unmarried</option>
            </select>
        </div>

        {{--Address Proof--}}
        <div class="form-group col-md-8 {{ hasErrors('address_proof', $errors) }}">
            <label class="control-label" for="inputAddressProof">Address Proof</label>
            <input type="file" class="form-control" id="inputAddressProof" name="address_proof">
            <span class="help-block">
                <small>
                    <em>Only PDF and Image files. Maximum file size: 10MB</em>
                </small>
            </span>
        </div>

        {{--ID Proof--}}
        <div class="form-group col-md-8 {{ hasErrors('id_proof', $errors) }}">
            <label class="control-label" for="inputIDProof">ID Proof</label>
            <input type="file" class="form-control" id="inputIDProof" name="id_proof">
            <span class="help-block">
                <small>
                    <em>Only PDF and Image files. Maximum file size: 10MB</em>
                </small>
            </span>
        </div>

        {{--Products handled--}}
        <div class="form-group col-md-8 {{ hasErrors('products_handled', $errors) }}">
            <label class="control-label" for="inputProductsHandled">Products Handled</label>
            <input type="file" class="form-control" id="inputProductsHandled" name="products_handled">
            <span class="help-block">
                <small>
                    <em>Only PDF and Image files. Maximum file size: 10MB</em>
                </small>
            </span>
        </div>

        {{--Experience with Banks--}}
        <div class="form-group col-md-8 {{ hasErrors('experience_with_banks', $errors) }}">
            <label class="control-label" for="inputExperienceWithBanks">Experience with Bank</label>
            <input type="file" class="form-control" id="inputExperienceWithBanks" name="experience_with_banks">
            <span class="help-block">
                <small>
                    <em>Only PDF and Image files. Maximum file size: 10MB</em>
                </small>
            </span>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.users.show', $user->id) }}" class="btn btn-default" >Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection
