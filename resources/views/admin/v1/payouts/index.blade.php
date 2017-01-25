@extends('admin.v1.layouts.master')

@section('main')
<h1>View all Payouts</h1>
@include('admin.v1.layouts.partials._messages')
<hr>
<div class="bs-component">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#builder" data-toggle="tab">Builder payout</a></li>
        <li><a href="#referral" data-toggle="tab">Referral payout</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade active in" id="builder">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Builder Name</th>
                        <th>Percentage</th>
                        <th>Status</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                    <tr>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->builder->name }}</td>
                        <td>{{ $project->payoutsTrashed->first()->percentage }}</td>
                        <td>{{ indexStatus($project->payoutsTrashed->first()) }}</td>
                        <td class="pull-right">
                            <a href="{{ route('admin.v1.payouts.builder.edit', $project->id) }}" class="btn btn-xs btn-info">
                                Edit
                            </a>
                        </td>

                        <td>
                            <form action="{{ route('admin.v1.payouts.destroy', $project->payoutsTrashed->first()->id) }}" class="form-inline inline-block" method="post">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                <button class="btn btn-xs btn-{{$project->payoutsTrashed->first()->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$project->payoutsTrashed->first()->trashed()? 'Enable project payout' : 'Disable project payout'}}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr class="text-info">
                        <td colspan="6">No Builder Payouts</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $projects->render() }}
        </div>
        <div class="tab-pane fade" id="referral">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Referral Name</th>
                        <th>Percentage</th>
                        <th>Total Paid Amount</th>
                        <th>Status</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrals as $referral)
                    <tr>
                        <td>{{ $referral->present()->name }}</td>
                        <td>{{ $referral->payoutsTrashed->first()->percentage }}</td>
                        <td>{{ $referral->payoutsTrashed->first()->total_paid_amount }}</td>
                        <td>{{ indexStatus($referral->payoutsTrashed->first()) }}</td>
                        <td class="pull-right">
                            <a href="{{ route('admin.v1.payouts.referral.edit', $referral->id) }}" class="btn btn-xs btn-info">
                                Edit
                            </a>
                        </td>

                        <td>
                            <form action="{{ route('admin.v1.payouts.destroy', $referral->payoutsTrashed->first()->id) }}" class="form-inline inline-block" method="post">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                <button class="btn btn-xs btn-{{$referral->payoutsTrashed->first()->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$referral->payoutsTrashed->first()->trashed()? 'Enable referral payout' : 'Disable referral payout'}}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr class="text-info">
                        <td colspan="6">No Referral Payouts</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $referrals->render() }}
        </div>
    </div>
</div>
@endsection
