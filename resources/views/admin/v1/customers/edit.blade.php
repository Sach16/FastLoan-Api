@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit customer</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.customers.update', $customer->id) }}" class="form-horizontal"
    enctype="multipart/form-data" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--Profile picture--}}
        <div class="form-group col-md-8 {{ hasErrors('profile_picture', $errors) }}">
            <label class="control-label" for="inputProfilePicture">Profile picture</label>
            <input type="file" class="form-control" id="inputProfilePicture" name="profile_picture">
            <span class="help-block">
                    <small>
                        <em>Only Image files with maximum 100 x 100 resolution </em>
                    </small>
            </span>
        </div>

        {{--First Name--}}
        <div class="form-group col-md-8 {{ hasErrors('first_name', $errors) }}">
            <label class="control-label" for="inputFirstName">First Name</label>
            <input type="text" class="form-control" id="inputFirstName" name="first_name" value="{{ $customer->first_name }}">
        </div>

        {{--Last Name--}}
        <div class="form-group col-md-8 {{ hasErrors('last_name', $errors) }}">
            <label class="control-label" for="inputLastName">Last Name</label>
            <input type="text" class="form-control" id="inputLastName" name="last_name" value="{{ $customer->last_name }}">
        </div>

        {{--Email--}}
        <div class="form-group col-md-8 {{ hasErrors('email', $errors) }}">
            <label class="control-label" for="inputEmail">Email ID</label>
            <input type="email" class="form-control" id="inputEmail" name="email" value="{{ $customer->email }}">
        </div>

        {{--Phone--}}
        <div class="form-group col-md-8 {{ hasErrors('phone', $errors) }}">
            <label class="control-label" for="inputPhone">Phone Number</label>
            <input type="text" class="form-control" id="inputPhone" name="phone" value="{{ $customer->phone }}">
        </div>

        {{--Designation--}}
        <div class="form-group col-md-8 {{ hasErrors('phone', $errors) }}">
            <label class="control-label" for="inputDesignation">Designation</label>
            <select name="designation_id" id="inputDesignation" class="form-control" data-chosen>
                <option selected disabled>Select designation</option>
                @foreach($designations as $designation)
                    <option value="{{ $designation->id }}" {{ isMatching($designation->id, $customer->designation->id) }}>
                        {{ $designation->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{--Date of Birth--}}
        <div class="form-group col-md-8 {{ hasErrors('dob', $errors) }}">
            <label class="control-label" for="inputDateOfBirth">Date of Birth</label>
            <input type="text" class="form-control" data-dob-date
                   id="inputDateOfBirth" name="settings[dob]" value="{{ (isset($customer->settings['dob'])) ? date('d-m-Y',strtotime($customer->settings['dob'])) : "" }}">
        </div>

        {{--Permanent Account Number--}}
        <div class="form-group col-md-8 {{ hasErrors('pan', $errors) }}">
            <label class="control-label" for="inputPermanentAccountNumber">Permanent Account Number</label>
            <input type="text" class="form-control"
                   id="inputPermanentAccountNumber" name="settings[pan]"
                   value="{{ (isset($customer->settings['pan'])) ? $customer->settings['pan'] : "" }}">
        </div>

        {{--Skype--}}
        <div class="form-group col-md-8 {{ hasErrors('skype', $errors) }}">
            <label class="control-label" for="inputSkype">Skype ID</label>
            <input type="text" class="form-control"
                   id="inputSkype" name="settings[skype]"
                   value="{{ (isset($customer->settings['skype'])) ? $customer->settings['skype'] : "" }}">
        </div>

        {{--Company--}}
        <div class="form-group col-md-8 {{ hasErrors('company', $errors) }}">
            <label class="control-label" for="inputCompany">Company Name</label>
            <input type="text" class="form-control"
                   id="inputCompany" name="settings[company]"
                   value="{{ (isset($customer->settings['company'])) ? $customer->settings['company'] : "" }}">
        </div>

        {{--Facetime--}}
        <div class="form-group col-md-8 {{ hasErrors('facetime', $errors) }}">
            <label class="control-label" for="inputFacetime">Facetime ID</label>
            <input type="text" class="form-control"
                   id="inputFacetime" name="settings[facetime]"
                   value="{{ (isset($customer->settings['facetime'])) ? $customer->settings['facetime'] : "" }}">
        </div>

        {{--Education--}}
        <div class="form-group col-md-8 {{ hasErrors('education', $errors) }}">
            <label class="control-label" for="inputEducation">Education</label>
            <input type="text" class="form-control"
                   id="inputEducation" name="settings[education]"
                   value="{{ (isset($customer->settings['education'])) ? $customer->settings['education'] : "" }}">
        </div>

        {{--Net Income--}}
        <div class="form-group col-md-8 {{ hasErrors('net_income', $errors) }}">
            <label class="control-label" for="inputNetIncome">Net Income</label>
            <input type="text" class="form-control"
                   id="inputNetIncome" name="settings[net_income]"
                   value="{{ (isset($customer->settings['net_income'])) ? $customer->settings['net_income'] : "" }}">
        </div>

        {{--Profession--}}
        <div class="form-group col-md-8 {{ hasErrors('profession', $errors) }}">
            <label class="control-label" for="inputProfession">Profession</label>
            <select name="settings[profession]" id="inputProfession" class="form-control" data-chosen>
                <option value="" selected disabled>Select profession</option>
                <option value="Salaried" {{ isMatching('Salaried', @$customer->settings['profession']) }}>
                    Salaried
                </option>
                <option value="Doctor" {{ isMatching('Doctor', @$customer->settings['profession']) }}>
                    Doctor
                </option>
                <option value="Self Employed-Professionals"
                        {{ isMatching('Self Employed-Professionals', @$customer->settings['profession']) }}>
                    Self employed professional
                </option>
                <option value="Self Employed-Others"
                        {{ isMatching('Self Employed-Others', @$customer->settings['profession']) }}>
                    Self employed others
                </option>
            </select>
        </div>

        {{--CIBIL Score--}}
        <div class="form-group col-md-8 {{ hasErrors('cibil_score', $errors) }}">
            <label class="control-label" for="inputCibilScore">CIBIL Score</label>
            <input type="text" class="form-control"
                   id="inputCibilScore" name="settings[cibil_score]"
                   value="{{ (isset($customer->settings['cibil_score'])) ? $customer->settings['cibil_score'] : "" }}">
        </div>

        {{--CIBIL Status--}}
        <div class="form-group col-md-8 {{ hasErrors('cibil_status', $errors) }}">
            <label class="control-label" for="inputCibilStatus">CIBIL Status</label>
            <select name="settings[cibil_status]" id="inputCibilStatus" class="form-control" data-chosen>
                <option value="" disabled selected>Select status</option>
                <option value="Settled A/C" {{ isMatching('Settled A/C', @$customer->settings['cibil_status']) }}>Settled A/C</option>
                <option value="Written off A/C" {{ isMatching('Written off A/C', @$customer->settings['cibil_status']) }}>Written off A/C</option>
                <option value="Overdue A/C" {{ isMatching('Overdue A/C',@$customer->settings['cibil_status']) }}>Overdue A/C</option>
                <option value="Good A/C" {{ isMatching('Good A/C', @$customer->settings['cibil_status']) }}>Good A/C</option>
            </select>
        </div>

        {{--Salary Bank--}}
        <div class="form-group col-md-8 {{ hasErrors('salary_bank', $errors) }}">
            <label class="control-label" for="inputCibilStatus">Salary Bank Name</label>
            <input type="text" class="form-control"
                   id="inputCibilStatus" name="settings[salary_bank]"
                   value="{{ (isset($customer->settings['salary_bank'])) ? $customer->settings['salary_bank'] : "" }}">
        </div>

        {{--Contact Time--}}
        <div class="form-group col-md-8 {{ hasErrors('contact_time', $errors) }}">
            <label class="control-label" for="inputTime">Contact Time</label>
            <input type="text" class="form-control"
                   id="inputTime" name="settings[contact_time]"
                   value="{{ (isset($customer->settings['contact_time'])) ? $customer->settings['contact_time'] : "" }}" data-timeSelector>
        </div>

        {{--Marital Status--}}
        <div class="form-group col-md-8 {{ hasErrors('marital_status', $errors) }}">
            <label class="control-label" for="inputMaritalStatus">Marital Status</label>
            <select name="settings[marital_status]" id="inputMaritalStatus" class="form-control" data-chosen>
                <option value="" disabled selected>Select marital status</option>
                <option value="Married" {{ isMatching('Married', @$customer->settings['marital_status']) }}>Married</option>
                <option value="Unmarried" {{ isMatching('Unmarried', @$customer->settings['marital_status']) }}>Unmarried</option>
            </select>
        </div>

        {{--Resident Status--}}
        <div class="form-group col-md-8 {{ hasErrors('resident_status', $errors) }}">
            <label class="control-label" for="inputResidentStatus">Resident Status</label>
            <select name="settings[resident_status]" id="inputResidentStatus" class="form-control" data-chosen>
                <option value="" disabled selected>Select resident status</option>
                <option value="Indian" {{ isMatching('Indian', @$customer->settings['resident_status']) }}>
                    Indian
                </option>
                <option value="PIO/OCI" {{ isMatching('PIO/OCI', @$customer->settings['resident_status']) }}>
                    PIO/OCI
                </option>
                <option value="NRI" {{ isMatching('NRI', @$customer->settings['resident_status']) }}>
                    NRI
                </option>
            </select>
        </div>

        {{--Aplha Street--}}
        <div class="form-group col-md-10 {{ hasErrors('alphaStreet', $errors) }}">
            <label class="control-label" for="inputAlphaStreet">Street #1</label>
            <input type="text" class="form-control" id="inputAlphaStreet" name="alphaStreet"
                   value="{{ $customer->addresses()->first()->alpha_street }}">
        </div>

        {{--Beta Street--}}
        <div class="form-group col-md-10 {{ hasErrors('betaStreet', $errors) }}">
            <label class="control-label" for="inputBetaStreet">Street #2</label>
            <input type="text" class="form-control" id="inputBetaStreet" name="betaStreet"
                   value="{{ $customer->addresses()->first()->beta_street }}">
        </div>

        {{--City id--}}
        <div class="form-group col-md-8 {{ hasErrors('city_id', $errors) }}">
            <label class="control-label" for="inputCityId">City</label>
            <input type="hidden" value="{{ ($customer->addresses->count() > 0 ) ? $customer->addresses->first()->city->id : "" }}" id="selected-city">
            <select name="city_id" id="inputCityId" class="form-control" data-city>
            </select>
        </div>

        {{--State--}}
        <div class="form-group col-md-8 {{ hasErrors('state', $errors) }}">
            <label class="control-label" for="inputState">State</label>
            <input type="text" class="form-control" id="inputState" name="state"
                   value="{{ $customer->addresses()->first()->state }}">
        </div>

        {{--Country--}}
        <div class="form-group col-md-8 {{ hasErrors('country', $errors) }}">
            <label class="control-label" for="inputCountry">Country</label>
            <input type="text" class="form-control" id="inputCountry" name="country"
                   value="{{ $customer->addresses()->first()->country }}">
        </div>

        {{--Zipcode--}}
        <div class="form-group col-md-6 {{ hasErrors('zipcode', $errors) }}">
            <label class="control-label" for="inputZipCode">Pincode</label>
            <input type="text" class="form-control" id="inputZipCode" name="zipcode"
                   value="{{ $customer->addresses()->first()->zip }}">
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.customers.show', $customer->id) }}" class="btn btn-default" >Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection
