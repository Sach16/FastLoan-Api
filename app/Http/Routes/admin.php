<?php

// Routes for the Admin Dashboard
Route::group(['middleware' => ['web'], 'prefix' => 'admin/v1'], function () {
    // Open routes
    Route::get('auth/login', 'AuthController@login')->name('admin.v1.auth.login.get');
    Route::post('auth/login', 'AuthController@postLogin')->name('admin.v1.auth.login.post');
    Route::get('auth/logout', 'AuthController@logout')->name('admin.v1.auth.logout.get');
    Route::get('password/email', 'PasswordController@getEmail')->name('admin.v1.password.email.get');
    Route::post('password/email', 'PasswordController@postEmail')->name('admin.v1.password.email.post');
    Route::get('password/reset/{token}', 'PasswordController@getReset')->name('admin.v1.password.reset.get');
    Route::post('password/reset', 'PasswordController@postReset')->name('admin.v1.password.reset.post');
    Route::get('password/change', 'PasswordController@showChangePasswordForm')->name('admin.v1.password.changeForm');
    Route::post('password/change', 'PasswordController@changePassword')->name('admin.v1.password.update.post');
    // Protected routes
    Route::group(['middleware' => ['auth.admin'], 'after' => 'no-cache'], function () {
        Route::get('/', 'DashboardController@index')->name('admin.v1.dashboard.index');
        Route::resource('banks', 'BanksController');
        Route::resource('banks.projects', 'BanksProjectsController');
        Route::resource('banks.documents', 'BanksDocumentsController');
        Route::resource('campaigns', 'CampaignsController');
        Route::resource('projects', 'ProjectsController');
        Route::resource('projects.queries', 'ProjectsQueriesController');
        Route::get('projects/bulk/create', 'ProjectsBulkController@create')->name('admin.v1.projects.bulk.create');
        Route::post('projects/bulk/create', 'ProjectsBulkController@store')->name('admin.v1.projects.bulk.store');
        Route::get('projects/bulk/template', 'ProjectsBulkController@template')->name('admin.v1.projects.bulk.template');
        Route::get('projects/bulk/errors/{errors}', 'ProjectsBulkController@errors')->name('admin.v1.projects.bulk.errors');
        Route::resource('tasks', 'TasksController');
        Route::resource('tasks.documents', 'TasksDocumentsController');
        Route::resource('tasks-transfer', 'TasksTransferController');
        Route::resource('task-stages', 'TaskStagesController');
        Route::resource('task-statuses', 'TaskStatusesController');
        Route::resource('builders', 'BuildersController');
        Route::resource('customers', 'CustomersController');
        Route::get('customers/bulk/create', 'CustomersController@bulk_create')->name('admin.v1.customers.bulk.create');
        Route::post('customers/bulk/create', 'CustomersController@bulk_store')->name('admin.v1.customers.bulk.store');
        Route::get('customers/bulk/template', 'CustomersController@template')->name('admin.v1.customers.bulk.template');
        Route::get('customers/bulk/errors/{errors}', 'CustomersController@errors')->name('admin.v1.customers.bulk.errors');
        Route::resource('customers.documents', 'CustomersDocumentsController');
        Route::resource('customers.loans', 'CustomersLoansController');
        Route::get('customers/{customer}/documents-download', 'CustomersDocumentsController@download')->name('admin.v1.customers.download');
        Route::resource('designations', 'DesignationsController');
        Route::resource('teams', 'TeamsController');
        Route::get('teams/{team}/settings/{member}', 'TeamsController@editSettings')->name('admin.v1.teams.edit.settings');
        Route::post('teams/{team}/settings/{member}', 'TeamsController@updateSettings')->name('admin.v1.teams.edit.settings.update');
        Route::get('teams/{team}/remove/{member}', 'TeamsController@removeDsaMember')->name('admin.v1.teams.remove.from.team');
        Route::post('teams/{team}/remove/{member}', 'TeamsController@updateRemoveDsaMember')->name('admin.v1.teams.remove.from.team');
        // to remove the owner from team
        Route::get('teams/{team}/remove-owner/{member}', 'TeamsController@removeDsaOwner')->name('admin.v1.teams.remove.owner.from.team');
        Route::post('teams/{team}/remove-owner/{member}', 'TeamsController@updateRemoveDsaOwner')->name('admin.v1.teams.remove.owner.from.team');

        // add multiple owner for the team
        Route::get('teams/{team}/multi-owner/{member}', 'TeamsController@updateMultiOwner')->name('admin.v1.teams.multi.owner.for.team');

        Route::resource('teams.calendars', 'TeamsCalendersController');
        Route::resource('teams.attendances', 'TeamsAttendancesController');
        Route::get('audits', 'AuditsController@index')->name('admin.v1.audits.index');
        Route::get('audits/logins', 'AuditsController@logins')->name('admin.v1.audits.logins');
        Route::resource('leads', 'LeadsController');

        Route::get('leads/bulk/create', 'LeadsBulkController@create')->name('admin.v1.leads.bulk.create');
        Route::post('leads/bulk/create', 'LeadsBulkController@store')->name('admin.v1.leads.bulk.store');
        Route::get('leads/bulk/template', 'LeadsBulkController@template')->name('admin.v1.leads.bulk.template');
        Route::get('leads/bulk/errors/{errors}', 'LeadsBulkController@errors')->name('admin.v1.leads.bulk.errors');
        Route::get('leads/bulk/export', 'LeadsBulkController@export')->name('admin.v1.leads.bulk.export');

        Route::resource('states', 'StatesController');
        Route::resource('localities', 'LocalitiesController');
        Route::resource('cities', 'CitiesController');
        Route::resource('users', 'UsersController');
        Route::get('users-phone/{user}', 'UsersPhoneController@show')->name('admin.v1.users.phone.show');
        Route::post('users-phone/{user}', 'UsersPhoneController@update')->name('admin.v1.users.phone.update');
        Route::get('referrals-phone/{user}', 'ReferralsPhoneController@show')->name('admin.v1.referrals.phone.show');
        Route::post('referrals-phone/{user}', 'ReferralsPhoneController@update')->name('admin.v1.referrals.phone.update');
        Route::get('referrals-team/{user}', 'ReferralsTeamController@show')->name('admin.v1.referrals.team.show');
        Route::post('referrals-team/{user}', 'ReferralsTeamController@update')->name('admin.v1.referrals.team.update');
        Route::resource('loans', 'LoansController');
        Route::resource('loans.documents', 'LoansDocumentsController');
        Route::resource('sources', 'SourcesController');
        Route::resource('banks.products', 'BanksProductsController');

        Route::resource('referrals', 'ReferralsController');

        Route::resource('payouts', 'PayoutsController');
        Route::resource('payouts/builder', 'BuildersPayoutsController');
        Route::resource('payouts/referral', 'ReferralPayoutsController');

        Route::resource('projectbanks', 'ProjectBanksController');
        Route::resource('products', 'ProductsController');
        Route::resource('feedback/category', 'FeedbackCategoryController');
        Route::resource('feedback/question', 'FeedbackQuestionController');
        // Autocomplete
        Route::get('api/teams', 'ApiController@teamIndex');
        Route::get('api/teams/{team}', 'ApiController@teamShow');
        Route::get('api/addresses/cities', 'ApiController@cities');
        Route::get('api/members', 'ApiController@membersIndex');
        Route::get('api/dsa-owner/members', 'ApiController@ownerMembersIndex');
        Route::get('api/nonmembers', 'ApiController@nonmembersIndex');
        Route::get('api/builders', 'ApiController@buildersIndex');
        Route::get('api/sources', 'ApiController@sourcesIndex');
        Route::get('api/loans', 'ApiController@loansIndex');
        Route::get('api/loantypes', 'ApiController@loanTypesIndex');
        Route::get('api/projects', 'ApiController@projectsIndex');
        Route::get('api/referrals', 'ApiController@referralsIndex');
        Route::get('api/states', 'ApiController@states');
        Route::get('api/loan-statuses', 'ApiController@loanStatusesIndex');
        Route::get('api/members-bank', 'ApiController@membersBank');
        Route::get('api/members-project-approval', 'ApiController@membersApprovalRequest');
        Route::get('api/banks', 'ApiController@bankIndex');
        Route::get('api/bank-members', 'ApiController@bankMembers');
        Route::get('api/payout-referral', 'ApiController@PayoutReferralsIndex');
        Route::get('api/payout-projects', 'ApiController@PayoutProjectsIndex');
    });
});
