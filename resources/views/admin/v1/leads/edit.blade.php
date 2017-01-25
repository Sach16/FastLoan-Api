@extends('admin.v1.layouts.master')

@section('main')
<h1>Edit Lead</h1>
@include('admin.v1.layouts.partials._messages')
<hr>


<form action="{{ route('admin.v1.leads.update', $lead->id) }}" class="form-horizontal"
    enctype="multipart/form-data" method="post">

    {{ csrf_field() }}
    {{ method_field('put') }}

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
        <input type="text" class="form-control" id="inputFirstName" name="first_name" value="{{ $lead->user->first_name }}">
    </div>


    {{--Last Name--}}
    <div class="form-group col-md-8 {{ hasErrors('last_name', $errors) }}">
        <label class="control-label" for="inputLastName">Last Name</label>
        <input type="text" class="form-control" id="inputLastName" name="last_name" value="{{ $lead->user->last_name }}">
    </div>

    {{--Email--}}
    <div class="form-group col-md-8 {{ hasErrors('email', $errors) }}">
        <label class="control-label" for="inputEmail">Email ID</label>
        <input type="email" class="form-control" id="inputEmail" name="email" value="{{ $lead->user->email }}">
    </div>

    {{--Phone--}}
    <div class="form-group col-md-8 {{ hasErrors('phone', $errors) }}">
        <label class="control-label" for="inputPhone">Phone Number</label>
        <input type="text" class="form-control" id="inputPhone" name="phone" value="{{ $lead->user->phone }}" readonly>
    </div>

    {{--Date of Birth--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.dob', $errors) }}">
        <label class="control-label" for="inputDateOfBirth">Date of Birth</label>
        <input type="text" class="form-control" data-dob-date
               id="inputDateOfBirth" name="settings[dob]" value="{{ date('d-m-Y',strtotime($lead->user->settings['dob'])) }}">
    </div>

    {{--Aplha Street--}}
    <div class="form-group col-md-10 {{ hasErrors('alphaStreet', $errors) }}">
        <label class="control-label" for="inputAlphaStreet">Street #1</label>
        <input type="text" class="form-control" id="inputAlphaStreet" name="alphaStreet"
               value="{{ $lead->user->addresses()->first()->alpha_street }}">
    </div>

    {{--Beta Street--}}
    <div class="form-group col-md-10 {{ hasErrors('betaStreet', $errors) }}">
        <label class="control-label" for="inputBetaStreet">Street #2</label>
        <input type="text" class="form-control" id="inputBetaStreet" name="betaStreet"
               value="{{ $lead->user->addresses()->first()->beta_street }}">
    </div>

    {{--City--}}
    <div class="form-group col-md-8 {{ hasErrors('city_id', $errors) }}">
        <label class="control-label" for="inputCity">City</label>
        <input type="hidden" value="{{ ($lead->user->addresses->count() > 0 ) ? $lead->user->addresses()->first()->city->id : "" }}" id="selected-city">
        <select name="city_id" id="inputCity" class="form-control" data-city>
        </select>
    </div>

    {{--State--}}
    <div class="form-group col-md-8 {{ hasErrors('state', $errors) }}">
        <label class="control-label" for="inputState">State</label>
        <input type="text" class="form-control" id="inputState" name="state"
               value="{{ $lead->user->addresses()->first()->state }}">
    </div>

    {{--Country--}}
    <div class="form-group col-md-8 {{ hasErrors('country', $errors) }}">
        <label class="control-label" for="inputCountry">Country</label>
        <input type="text" class="form-control" id="inputCountry" name="country"
               value="{{ $lead->user->addresses()->first()->country }}">
    </div>

    {{--Zipcode--}}
    <div class="form-group col-md-6 {{ hasErrors('zipcode', $errors) }}">
        <label class="control-label" for="inputZipCode">Pincode</label>
        <input type="text" class="form-control" id="inputZipCode" name="zipcode"
               value="{{ $lead->user->addresses()->first()->zip }}">
    </div>

    {{--Permanent Account Number--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.pan', $errors) }}">
        <label class="control-label" for="inputPermanentAccountNumber">Permanent Account Number</label>
        <input type="text" class="form-control"
               id="inputPermanentAccountNumber" name="settings[pan]"
               value="{{ @$lead->user->settings['pan'] }}">
    </div>

    {{--Skype--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.skype', $errors) }}">
        <label class="control-label" for="inputSkype">Skype ID</label>
        <input type="text" class="form-control"
               id="inputSkype" name="settings[skype]"
               value="{{ @$lead->user->settings['skype'] }}">
    </div>

    {{--Company--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.company', $errors) }}">
        <label class="control-label" for="inputCompany">Company Name</label>
        <input type="text" class="form-control"
               id="inputCompany" name="settings[company]"
               value="{{ @$lead->user->settings['company'] }}">
    </div>

    {{--Facetime--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.facetime', $errors) }}">
        <label class="control-label" for="inputFacetime">Facetime ID</label>
        <input type="text" class="form-control"
               id="inputFacetime" name="settings[facetime]"
               value="{{ @$lead->user->settings['facetime'] }}">
    </div>

    {{--Education--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.education', $errors) }}">
        <label class="control-label" for="inputEducation">Education</label>
        <input type="text" class="form-control"
               id="inputEducation" name="settings[education]"
               value="{{ @$lead->user->settings['education'] }}">
    </div>

    {{--Net Income--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.net_income', $errors) }}">
        <label class="control-label" for="inputNetIncome">Net Income</label>
        <input type="text" class="form-control"
               id="inputNetIncome" name="settings[net_income]"
               value="{{ @$lead->user->settings['net_income'] }}">
    </div>



    {{--Profession--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.profession', $errors) }}">
        <label class="control-label" for="inputProfession">Profession</label>
        <select name="settings[profession]" id="inputProfession" class="form-control" data-chosen>
            <option value="-1" selected disabled>Select profession</option>
            <option value="Salaried" {{ isMatching('Salaried', @$lead->user->settings['profession']) }}>
                    Salaried
            </option>
            <option value="Doctor" {{ isMatching('Doctor', @$lead->user->settings['profession']) }}>
                    Doctor
            </option>
            <option value="Self Employed-Professionals"
                {{ isMatching('Self Employed-Professionals', @$lead->user->settings['profession']) }}>
                Self employed professional
            </option>
            <option value="Self Employed-Others"
                    {{ isMatching('Self Employed-Others', @$lead->user->settings['profession']) }}>
                    Self employed others
            </option>
        </select>
    </div>


    {{--Existing Loan emi--}}
    <div class="form-group col-md-8 {{ hasErrors('existing_loan_emi', $errors) }}">
        <label class="control-label" for="inputExistingLoanEmi">Existing Loan EMI Amount</label>
        <input type="text" class="form-control"
               id="inputExistingLoanEmi" name="existing_loan_emi"
               value="{{ @$lead->existing_loan_emi }}">
    </div>



    {{--Source--}}
    <div class="form-group col-md-8 {{ hasErrors('source_id', $errors) }}">
        <label class="control-label" for="source_id_selected">Source</label>
        <input type="hidden" value="{{ $lead->source_id }}" id="selected-source">
        <select name="source_id" id="inputSourceId" class="form-control" data-sources></select>
    </div>


    {{-- Referrals --}}
    <div class="form-group col-md-8 {{ hasErrors('referral_id', $errors) }} ">
        <label class="control-label" for="referral_id">Referral</label>
        <input type="hidden" value="{{ (($lead->referrals->count() != 0) ? $lead->referrals->first()->id : "") }}" id="selected-referrals">
        <select name="referral_id" id="inputReferral" class="form-control" data-referrals {{ (($lead->referrals->count() == 0)) ? "disabled" : "" }}></select>
    </div>


    {{--CIBIL Score--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.cibil_score', $errors) }}">
        <label class="control-label" for="inputCibilScore">CIBIL Score</label>
        <input type="text" class="form-control"
               id="inputCibilScore" name="settings[cibil_score]"
               value="{{ @$lead->user->settings['cibil_score'] }}">
    </div>

    {{--CIBIL Status--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.cibil_status', $errors) }}">
        <label class="control-label" for="inputCibilStatus">CIBIL Status</label>
        <select name="settings[cibil_status]" id="inputCibilStatus" class="form-control" data-chosen>
            <option value="-1" disabled selected>Select status</option>
            <option value="Settled A/C" {{ isMatching('Settled A/C', @$lead->user->settings['cibil_status']) }}>Settled A/C</option>
            <option value="Written off A/C" {{ isMatching('Written off A/C', @$lead->user->settings['cibil_status']) }}>Written off A/C</option>
            <option value="Overdue A/C" {{ isMatching('Overdue A/C', @$lead->user->settings['cibil_status']) }}>Overdue A/C</option>
            <option value="Good A/C" {{ isMatching('Good A/C', @$lead->user->settings['cibil_status']) }}>Good A/C</option>
        </select>
    </div>

    {{--Salary Bank--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.salary_bank', $errors) }}">
        <label class="control-label" for="inputCibilStatus">Salary Bank Name</label>
        <input type="text" class="form-control"
               id="inputCibilStatus" name="settings[salary_bank]"
               value="{{ @$lead->user->settings['salary_bank'] }}">
    </div>

    {{--Contact Time--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.contact_time', $errors) }}">
        <label class="control-label" for="inputTime">Contact Time</label>
        <input type="text" class="form-control"
               id="inputTime" name="settings[contact_time]"
               value="{{ @$lead->user->settings['contact_time'] }}" data-timeSelector>
    </div>

    {{--Marital Status--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.marital_status', $errors) }}">
        <label class="control-label" for="inputMaritalStatus">Marital Status</label>
        <select name="settings[marital_status]" id="inputMaritalStatus" class="form-control" data-chosen>
            <option value="-1" disabled selected>Select marital status</option>
            <option value="Married" {{ isMatching('Married', @$lead->user->settings['marital_status']) }}>Married</option>
            <option value="Unmarried" {{ isMatching('Unmarried', @$lead->user->settings['marital_status']) }}>Unmarried</option>
        </select>
    </div>

    {{--Resident Status--}}
    <div class="form-group col-md-8 {{ hasErrors('settings.resident_status', $errors) }}">
        <label class="control-label" for="inputResidentStatus">Resident Status</label>
        <select name="settings[resident_status]" id="inputResidentStatus" class="form-control" data-chosen>
            <option value="-1" disabled selected>Select resident status</option>
            <option value="Indian" {{ isMatching('Indian', @$lead->user->settings['resident_status']) }}>
                    Indian
            </option>
            <option value="PIO/OCI" {{ isMatching('PIO/OCI', @$lead->user->settings['resident_status']) }}>
                    PIO/OCI
            </option>
            <option value="NRI" {{ isMatching('NRI', @$lead->user->settings['resident_status']) }}>
                    NRI
            </option>
        </select>
    </div>

     {{--Assign to--}}
    <div class="form-group col-md-8 {{ hasErrors('assigned_to', $errors) }}">
        <label class="control-label" for="assigned_to">Assign to</label>
        <input type="hidden" value="{{ $lead->assigned_to }}" id="selected-member">
        <select name="assigned_to" id="inputAssignedTo" class="form-control" data-dsa-owners></select>
    </div>


    {{--Submit--}}
    <div class="form-group">
        <div class="col-lg-10 col-lg-offset-2">
            <a href="{{ route('admin.v1.leads.show', $lead->id) }}" class="btn btn-default" >Back</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>

</form>
@endsection
