@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        @include('parts.forms.form_field', [
                            'labelName' => 'Username', 'name' => 'name',
                            'type' => 'text', 'required' => 'true'
                            ])

                        @include('parts.forms.form_field', [
                            'labelName' => 'Email', 'name' => 'email',
                            'type' => 'email', 'required' => 'true'
                            ])

                        @include('parts.forms.form_field', [
                            'labelName' => 'Password', 'name' => 'password', 'type' => 'password',
                            'autocomplete' => 'new-password', 'required' => 'true'
                            ])

                        @include('parts.forms.form_field', [
                            'labelName' => 'Confirm Password', 'name' => 'password_confirmation', 'type' => 'password',
                            'autocomplete' => 'new-password', 'required' => 'true'
                            ])

                        <hr>
                        <h4>Personal details</h4>
                        @include('parts.forms.form_field', ['labelName' => 'Last Name', 'name' => 'last_name', 'type' => 'text',
                            'required' => 'true'
                            ])

                        @include('parts.forms.form_field', ['labelName' => 'First Name', 'name' => 'first_name', 'type' => 'text',
                            'required' => 'true'
                            ])

                        @include('parts.forms.static_select', ['labelName' => 'Gender', 'name' => 'gender', 'options' => 'gender'])

                        <div class="form-group row">
                            <label for="nationality" class="col-md-4 col-form-label text-md-right">Nationality</label>

                            <div class="col-md-6">
                                @include('parts.forms.nationality')
                            </div>
                        </div>


                        @include('parts.forms.form_field', ['labelName' => 'Date of birth', 'name' => 'date_of_birth', 'type' => 'date'])
                        @include('parts.forms.form_field', ['labelName' => 'Phone Number', 'name' => 'phone_number', 'type' => 'tel', 'pattern' => '(\+[0-9]{11})|([0-9]{13})|([0-9]{10})'])
                        @include('parts.forms.form_field', ['labelName' => 'Drivers license', 'name' => 'drivers_licence', 'type' => 'text'])

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
