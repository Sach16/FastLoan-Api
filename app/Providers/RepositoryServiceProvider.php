<?php

namespace Whatsloan\Providers;

use Illuminate\Support\ServiceProvider;
use Whatsloan\Repositories\Leads\Contract as ILeads;
use Whatsloan\Repositories\Leads\Repository as Leads;
use Whatsloan\Repositories\Campaigns\Contract as ICampaigns;
use Whatsloan\Repositories\Campaigns\Repository as Campaigns;
use Whatsloan\Repositories\Users\Contract as IUsers;
use Whatsloan\Repositories\Users\Repository as Users;
use Whatsloan\Repositories\Teams\Contract as ITeams;
use Whatsloan\Repositories\Teams\Repository as Teams;
use Whatsloan\Repositories\Homes\Contract as IHomes;
use Whatsloan\Repositories\Homes\Repository as Homes;
use Whatsloan\Repositories\Tasks\Contract as ITasks;
use Whatsloan\Repositories\Tasks\Repository as Tasks;
use Whatsloan\Repositories\Attendances\Contract as IAttendances;
use Whatsloan\Repositories\Attendances\Repository as Attendances;
use Whatsloan\Repositories\Banks\Contract as IBanks;
use Whatsloan\Repositories\Banks\Repository as Banks;
use Whatsloan\Repositories\Projects\Contract as IProjects;
use Whatsloan\Repositories\Projects\Repository as Projects;
use Whatsloan\Repositories\Cities\Contract as ICities;
use Whatsloan\Repositories\Cities\Repository as Cities;
use Whatsloan\Repositories\Builders\Contract as IBuilders;
use Whatsloan\Repositories\Builders\Repository as Builders;
use Whatsloan\Repositories\Queries\Contract as IQueries;
use Whatsloan\Repositories\Queries\Repository as Queries;
use Whatsloan\Repositories\Loans\Contract as ILoans;
use Whatsloan\Repositories\Loans\Repository as Loans;
use Whatsloan\Repositories\TaskStages\Contract as ITaskStages;
use Whatsloan\Repositories\TaskStages\Repository as TaskStages;
use Whatsloan\Repositories\TaskStatuses\Contract as ITaskStatuses;
use Whatsloan\Repositories\TaskStatuses\Repository as TaskStatuses;
use Whatsloan\Repositories\ProjectStatuses\Contract as IProjectStatus;
use Whatsloan\Repositories\ProjectStatuses\Repository as ProjectStatus;
use Whatsloan\Repositories\Designations\Contract as IDesignation;
use Whatsloan\Repositories\Designations\Repository as Designation;
use Whatsloan\Repositories\Products\Contract as IProducts;
use Whatsloan\Repositories\Products\Repository as Products;
use Whatsloan\Repositories\Searchs\Contract as ISearchs;
use Whatsloan\Repositories\Searchs\Repository as Searchs;
use Whatsloan\Repositories\Sources\Contract as ISources;
use Whatsloan\Repositories\Sources\Repository as Sources;
use Whatsloan\Repositories\Types\Contract as ITypes;
use Whatsloan\Repositories\Types\Repository as Types;
use Whatsloan\Repositories\LoanAlert\Contract as ILoanAlert;
use Whatsloan\Repositories\LoanAlert\Repository as LoanAlert;
use Whatsloan\Repositories\Calendars\Contract as ICalendars;
use Whatsloan\Repositories\Calendars\Repository as Calendars;
use Whatsloan\Repositories\FeedbackCategories\Contract as IFeedbackCategories;
use Whatsloan\Repositories\FeedbackCategories\Repository as FeedbackCategories;
use Whatsloan\Repositories\Feedbacks\Contract as IFeedbacks;
use Whatsloan\Repositories\Feedbacks\Repository as Feedbacks;
use Whatsloan\Repositories\UserFeedback\Contract as IUserFeedbacks;
use Whatsloan\Repositories\UserFeedback\Repository as UserFeedbacks;
use Whatsloan\Repositories\States\Contract as IState;
use Whatsloan\Repositories\States\Repository as State;
use Whatsloan\Repositories\Localities\Contract as ILocality;
use Whatsloan\Repositories\Localities\Repository as Locality;

use Whatsloan\Repositories\Payouts\Contract as IPayout;
use Whatsloan\Repositories\Payouts\Repository as Payout;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Repository Mappings
     * @var array
     */
    protected $repositories = [
        ILeads::class => Leads::class,
        IUsers::class => Users::class,
        ITeams::class => Teams::class,
        IHomes::class => Homes::class,
        ITasks::class => Tasks::class,
        ICampaigns::class => Campaigns::class,
        IAttendances::class => Attendances::class,
        IBanks::class => Banks::class,
        IProjects::class => Projects::class,
        ICities::class => Cities::class,
        IBuilders::class => Builders::class,
        IQueries::class => Queries::class,
        ILoans::class => Loans::class,
        ITaskStages::class => TaskStages::class,
        ITaskStatuses::class => TaskStatuses::class,
        IProjectStatus::class => ProjectStatus::class,
        IProducts::class => Products::class,
        ISearchs::class => Searchs::class,
        IDesignation::class => Designation::class,
        ISources::class => Sources::class,
        ITypes::class => Types::class,
        ILoanAlert::class => LoanAlert::class,
        ICalendars::class => Calendars::class,
        IFeedbackCategories::class => FeedbackCategories::class,
        IFeedbacks::class => Feedbacks::class,
        IUserFeedback::class => UserFeedback::class,
        IState::class => State::class,
        ILocality::class => Locality::class,
        IPayout::class => Payout::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $contract => $repository) {
            $this->app->bind($contract, $repository);
        }
    }

}
