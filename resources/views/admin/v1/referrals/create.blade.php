@extends('admin.v1.layouts.master')

@section('main')
    <h1>Add new referral</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.referrals.store') }}" class="form-horizontal"
          enctype="multipart/form-data" method="post">
        {{ csrf_field() }}

        {{--First Name--}}
        <div class="form-group col-md-8 {{ hasErrors('first_name', $errors) }}">
            <label class="control-label" for="inputFirstName">First Name</label>
            <input type="text" class="form-control" id="inputFirstName" name="first_name" value="{{ old('first_name') }}">
        </div>

        {{--Last Name--}}
        <div class="form-group col-md-8 {{ hasErrors('last_name', $errors) }}">
            <label class="control-label" for="inputLastName">Last Name</label>
            <input type="text" class="form-control" id="inputLastName" name="last_name" value="{{ old('last_name') }}">
        </div>

        {{--Email--}}
        <div class="form-group col-md-8 {{ hasErrors('email', $errors) }}">
            <label class="control-label" for="inputEmail">Email ID</label>
            <input type="text" class="form-control" name="email" id="inputEmail" value="{{ old('email') }}">
        </div>

        {{--Phone--}}
        <div class="form-group col-md-8 {{ hasErrors('phone', $errors) }}">
            <label class="control-label" for="inputPhone">Phone Number</label>
            <input type="text" class="form-control" name="phone" id="inputPhone" value="{{ old('phone') }}">
        </div>

        {{--Designation--}}
        <div class="form-group col-md-8 {{ hasErrors('designation_id', $errors) }}">
            <label class="control-label" for="inputDesignation">Designation</label>
            <input type="hidden" value="{{old('designation_id') }}" id="selected-designation">
            <select name="designation_id" id="inputDesignation" class="form-control" data-chosen>
                <option selected disabled>Select designation</option>
                @foreach($designations as $designation)
                    <option value="{{ $designation->id }}" {{ isMatching($designation->id, old('settings.designation_id')) }} >
                        {{ $designation->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{--Date of Birth--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.dob', $errors) }}">
            <label class="control-label" for="inputDateOfBirth">Date of Birth</label>
            <input type="text" class="form-control" data-dob-date
                   id="inputDateOfBirth" name="settings[dob]" value="{{ old('settings.dob') }}">
        </div>


        {{--Aplha Street--}}
        <div class="form-group col-md-8 {{ hasErrors('alphaStreet', $errors) }}">
            <label class="control-label" for="inputAlphaStreet">Street #1</label>
            <input type="text" class="form-control" id="inputAlphaStreet" name="alphaStreet" value="{{ old('alphaStreet') }}">
        </div>

        {{--Beta Street--}}
        <div class="form-group col-md-8 {{ hasErrors('betaStreet', $errors) }}">
            <label class="control-label" for="inputBetaStreet">Street #2</label>
            <input type="text" class="form-control" id="inputBetaStreet" name="betaStreet" value="{{ old('betaStreet') }}">
        </div>

        {{--City--}}
        <div class="form-group col-md-8 {{ hasErrors('city_id', $errors) }}">
            <label class="control-label" for="inputCity">City</label>
            <input type="hidden" value="{{old('city_id') }}" id="selected-city">
            <select name="city_id" id="inputCity" class="form-control" data-city></select>
        </div>

        {{--State--}}
        <div class="form-group col-md-8 {{ hasErrors('state', $errors) }}">
            <label class="control-label" for="inputState">State</label>
            <input type="text" class="form-control" id="inputState" name="state" value="{{ old('state') }}">
        </div>

        {{--Country--}}
        <div class="form-group col-md-8 {{ hasErrors('country', $errors) }}">
            <label class="control-label" for="inputCountry">Country</label>
            <input type="text" class="form-control" id="inputCountry" name="country" value="{{ old('country') }}">
        </div>

        {{--Zipcode--}}
        <div class="form-group col-md-8 {{ hasErrors('zipcode', $errors) }}">
            <label class="control-label" for="inputZipCode">Pincode</label>
            <input type="text" class="form-control" id="inputZipCode" name="zipcode" value="{{ old('zipcode') }}">
        </div>

        {{--Permanent Account Number--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.pan', $errors) }}">
            <label class="control-label" for="inputPermanentAccountNumber">Permanent Account Number</label>
            <input type="text" class="form-control"
                   id="inputPermanentAccountNumber" name="settings[pan]"
                   value="{{ old('settings.pan') }}">
        </div>

        {{--Skype--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.skype', $errors) }}">
            <label class="control-label" for="inputSkype">Skype ID</label>
            <input type="text" class="form-control"
                   id="inputSkype" name="settings[skype]"
                   value="{{ old('settings.skype') }}">
        </div>

        {{--Company--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.company', $errors) }}">
            <label class="control-label" for="inputCompany">Company Name</label>
            <input type="text" class="form-control"
                   id="inputCompany" name="settings[company]"
                   value="{{ old('settings.company') }}">
        </div>

        {{--Facetime--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.facetime', $errors) }}">
            <label class="control-label" for="inputFacetime">Facetime ID</label>
            <input type="text" class="form-control"
                   id="inputFacetime" name="settings[facetime]"
                   value="{{ old('settings.facetime') }}">
        </div>

        {{--Education--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.education', $errors) }}">
            <label class="control-label" for="inputEducation">Education</label>
            <input type="text" class="form-control"
                   id="inputEducation" name="settings[education]"
                   value="{{ old('settings.education') }}">
        </div>

        {{--Net Income--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.net_income', $errors) }}">
            <label class="control-label" for="inputNetIncome">Net Income</label>
            <input type="text" class="form-control"
                   id="inputNetIncome" name="settings[net_income]"
                   value="{{ old('settings.net_income') }}">
        </div>

        {{--Profession--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.profession', $errors) }}">
            <label class="control-label" for="inputProfession">Profession</label>
            <select name="settings[profession]" id="inputProfession" class="form-control" data-chosen>
                <option disabled selected>Select profession</option>
                <option value="Salaried" {{ isMatching('Salaried', old('settings.profession')) }}>Salaried</option>
                <option value="Doctor" {{ isMatching('Doctor', old('settings.profession')) }}>Doctor</option>
                <option value="Self Employed-Professionals" {{ isMatching('Self Employed-Professionals', old('settings.profession')) }}>Self employed professional</option>
                <option value="Self Employed-Others" {{ isMatching('Self Employed-Others', old('settings.profession')) }}>Self employed others</option>
            </select>
        </div>

        {{--CIBIL Score--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.cibil_score', $errors) }}">
            <label class="control-label" for="inputCibilScore">CIBIL Score</label>
            <input type="text" class="form-control"
                   id="inputCibilScore" name="settings[cibil_score]"
                   value="{{ old('settings.cibil_score') }}">
        </div>

        {{--CIBIL Status--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.cibil_status', $errors) }}">
            <label class="control-label" for="inputCibilStatus">CIBIL Status</label>
            <select name="settings[cibil_status]" id="inputCibilStatus" class="form-control" data-chosen>
                <option disabled selected>Select status</option>
                <option value="Settled A/C" {{ isMatching('Settled A/C', old('settings.cibil_status')) }}>Settled A/C</option>
                <option value="Written off A/C" {{ isMatching('Written off A/C', old('settings.cibil_status')) }}>Written off A/C</option>
                <option value="Overdue A/C" {{ isMatching('Overdue A/C', old('settings.cibil_status')) }}>Overdue A/C</option>
                <option value="Good A/C" {{ isMatching('Good A/C', old('settings.cibil_status')) }}>Good A/C</option>
            </select>
        </div>

        {{--Salary Bank--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.salary_bank', $errors) }}">
            <label class="control-label" for="inputCibilStatus">Salary Bank Name</label>
            <input type="text" class="form-control"
                   id="inputCibilStatus" name="settings[salary_bank]"
                   value="{{ old('settings.salary_bank') }}">
        </div>

        {{--Contact Time--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.contact_time', $errors) }}">
            <label class="control-label" for="inputTime">Contact Time</label>
            <input type="text" class="form-control"
                   id="inputTime" name="settings[contact_time]"
                   value="{{ old('settings.contact_time') }}" data-timeSelector>
        </div>

        {{--Marital Status--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.marital_status', $errors) }}">
            <label class="control-label" for="inputMaritalStatus">Marital Status</label>
            <select name="settings[marital_status]" id="inputMaritalStatus" class="form-control" data-chosen>
                <option disabled selected>Select marital status</option>
                <option value="Married" {{ isMatching('Married', old('settings.marital_status')) }}>Married</option>
                <option value="Unmarried" {{ isMatching('Unmarried', old('settings.marital_status')) }}>Unmarried</option>
            </select>
        </div>

        {{--Resident Status--}}
        <div class="form-group col-md-8 {{ hasErrors('settings.resident_status', $errors) }}">
            <label class="control-label" for="inputResidentStatus">Resident Status</label>
            <select name="settings[resident_status]" id="inputResidentStatus" class="form-control" data-chosen>
                <option selected disabled>Select resident status</option>
                <option value="Indian" {{ isMatching('Indian', old('settings.resident_status')) }}>Indian</option>
                <option value="PIO/OCI" {{ isMatching('PIO/OCI', old('settings.resident_status')) }}>PIO/OCI</option>
                <option value="NRI" {{ isMatching('NRI', old('settings.resident_status')) }}>NRI</option>
            </select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
