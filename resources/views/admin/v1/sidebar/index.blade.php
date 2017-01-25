    <h4>Projects</h4>
    <div class="panel-group" role="tablist">
        {{--Banks--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#banks" data-toggle="collapse">Banks</a>
            </div>
            <ul class="list-group collapse {{ panelActive('banks') }} " role="tabpanel" id="banks">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.banks.index') }}">View all banks</a>
                    {{ selectedLink('admin.v1.banks.index') }}
                </li>

                @if (\Auth::user()->role === 'SUPER_ADMIN')
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.banks.create') }}">Add New Bank</a>
                    {{ selectedLink('admin.v1.banks.create') }}
                </li>
                @endif
            </ul>
        </div>
        {{--Builders--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#builders" data-toggle="collapse">Builders</a>
            </div>
            <ul class="list-group collapse {{ panelActive('builders') }} " role="tabpanel" id="builders">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.builders.index') }}">View all builders</a>
                    {{ selectedLink('admin.v1.builders.index') }}
                </li>
            </ul>
        </div>
        {{--Projects--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#projects" data-toggle="collapse">Projects</a>
            </div>
            <ul class="list-group collapse {{ panelActive('projects') }} " role="tabpanel" id="projects">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.projects.index') }}">View all projects</a>
                    {{ selectedLink('admin.v1.projects.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.projects.create') }}">Add New Project</a>
                    {{ selectedLink('admin.v1.projects.create') }}
                </li>
                @if (\Auth::user()->role === 'SUPER_ADMIN')
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.projects.bulk.create') }}">Bulk add projects</a>
                    {{ selectedLink('admin.v1.projects.bulk.create') }}
                </li>
                @endif
            </ul>
        </div>
        {{--Products--}}
        @if (\Auth::user()->role === 'SUPER_ADMIN')
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#products" data-toggle="collapse">Products</a>
            </div>
            <ul class="list-group collapse {{ panelActive('products') }} " role="tabpanel" id="products">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.products.index') }}">View all products</a>
                    {{ selectedLink('admin.v1.products.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.products.create') }}">Add new product</a>
                    {{ selectedLink('admin.v1.products.create') }}
                </li>
            </ul>
        </div>
        @endif

        <h4>Leads</h4>
        {{--Leads--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#leads" data-toggle="collapse">Leads</a>
            </div>
            <ul class="list-group collapse {{ panelActive('leads') }} " role="tabpanel" id="leads">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.leads.index') }}">View all leads</a>
                    {{ selectedLink('admin.v1.leads.index') }}
                </li>

                <li class="list-group-item">
                    <a href="{{ route('admin.v1.leads.create') }}">Add new lead</a>
                    {{ selectedLink('admin.v1.leads.create') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.leads.bulk.create') }}">Bulk add leads</a>
                    {{ selectedLink('admin.v1.leads.bulk.create') }}
                </li>
            </ul>
        </div>
        @if (\Auth::user()->role === 'SUPER_ADMIN')
        {{--Lead Sources--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#sources" data-toggle="collapse">Lead Sources</a>
            </div>
            <ul class="list-group collapse {{ panelActive('sources') }} " role="tabpanel" id="sources">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.sources.index') }}">View all sources</a>
                    {{ selectedLink('admin.v1.sources.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.sources.create') }}">Add new source</a>
                    {{ selectedLink('admin.v1.sources.create') }}
                </li>
            </ul>
        </div>
        @endif
        {{--Tasks--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#tasks" data-toggle="collapse">Tasks</a>
            </div>
            <ul class="list-group collapse {{ panelActive('task') }} " role="tabpanel" id="tasks">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.tasks.index') }}">View all tasks</a>
                    {{ selectedLink('admin.v1.tasks.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.tasks.create') }}">Add New Task</a>
                    {{ selectedLink('admin.v1.tasks.create') }}
                </li>
                @if (\Auth::user()->role === 'SUPER_ADMIN')
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.task-stages.index') }}">View all task stages</a>
                    {{ selectedLink('admin.v1.task-stages.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.task-stages.create') }}">Add new task stage</a>
                    {{ selectedLink('admin.v1.task-stages.create') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.task-statuses.index') }}">View all task statuses</a>
                    {{ selectedLink('admin.v1.task-statuses.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.task-statuses.create') }}">Add new task status</a>
                    {{ selectedLink('admin.v1.task-statuses.create') }}
                </li>
                @endif
            </ul>
        </div>
        {{--Campaigns--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#campaigns" data-toggle="collapse">Campaigns</a>
            </div>
            <ul class="list-group collapse {{ panelActive('campaigns') }} " role="tabpanel" id="campaigns">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.campaigns.index') }}">View all campaigns</a>
                    {{ selectedLink('admin.v1.campaigns.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.campaigns.create') }}">Add New Campaign</a>
                    {{ selectedLink('admin.v1.campaigns.create') }}
                </li>
            </ul>
        </div>
        {{--Referrals--}}
        @if (\Auth::user()->role === 'SUPER_ADMIN')
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#referrals" data-toggle="collapse">Referrals</a>
            </div>
            <ul class="list-group collapse {{ panelActive('referrals') }} " role="tabpanel" id="referrals">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.referrals.index') }}">View all referrals</a>
                    {{ selectedLink('admin.v1.referrals.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.referrals.create') }}">Add new referral</a>
                    {{ selectedLink('admin.v1.referrals.create') }}
                </li>
            </ul>
        </div>
        @endif
        {{--Payouts--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#payouts" data-toggle="collapse">Payouts</a>
            </div>
            <ul class="list-group collapse {{ panelActive('payouts') }} " role="tabpanel" id="payouts">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.payouts.index') }}">View all payouts</a>
                    {{ selectedLink('admin.v1.payouts.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.payouts.referral.create') }}">Add new referral payout</a>
                    {{ selectedLink('admin.v1.payouts.referral.create') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.payouts.builder.create') }}">Add new builders payout</a>
                    {{ selectedLink('admin.v1.payouts.builder.create') }}
                </li>
            </ul>
        </div>

        <h4>Addresses</h4>
        {{--Locations--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#locations" data-toggle="collapse">Cities</a>
            </div>
            <ul class="list-group collapse {{ panelActive('cities') }} " role="tabpanel" id="locations">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.cities.index') }}">View all cities</a>
                    {{ selectedLink('admin.v1.cities.index') }}
                </li>
                @if (\Auth::user()->role === 'SUPER_ADMIN')
                    <li class="list-group-item">
                        <a href="{{ route('admin.v1.cities.create') }}">Add new city</a>
                        {{ selectedLink('admin.v1.cities.create') }}
                    </li>
                @endif
            </ul>
        </div>
        {{--Localities--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#localities" data-toggle="collapse">Localities</a>
            </div>
            <ul class="list-group collapse {{ panelActive('localities') }} " role="tabpanel" id="localities">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.localities.index') }}">View all localities</a>
                    {{ selectedLink('admin.v1.localities.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.localities.create') }}">Add new locality</a>
                    {{ selectedLink('admin.v1.localities.create') }}
                </li>
            </ul>
        </div>
        {{--States--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#states" data-toggle="collapse">States</a>
            </div>
            <ul class="list-group collapse {{ panelActive('states') }} " role="tabpanel" id="states">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.states.index') }}">View all states</a>
                    {{ selectedLink('admin.v1.states.index') }}
                </li>
                @if (\Auth::user()->role === 'SUPER_ADMIN')
                    <li class="list-group-item">
                        <a href="{{ route('admin.v1.states.create') }}">Add new state</a>
                        {{ selectedLink('admin.v1.states.create') }}
                    </li>
                @endif
            </ul>
        </div>

        <h4>Users</h4>
        {{--Users--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#users" data-toggle="collapse">DSA Users</a>
            </div>
            <ul class="list-group collapse {{ panelActive('users') }} " role="tabpanel" id="users">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.users.index') }}">View all DSA users</a>
                    {{ selectedLink('admin.v1.users.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.users.create') }}">Add new DSA user</a>
                    {{ selectedLink('admin.v1.users.create') }}
                </li>
            </ul>
        </div>
        {{--Teams--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#teams" data-toggle="collapse">Teams</a>
            </div>
            <ul class="list-group collapse {{ panelActive('teams') }} " role="tabpanel" id="teams">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.teams.index') }}">View all teams</a>
                    {{ selectedLink('admin.v1.teams.index') }}
                </li>
                @if (\Auth::user()->role === 'SUPER_ADMIN')
                    <li class="list-group-item">
                        <a href="{{ route('admin.v1.teams.create') }}">Add new team</a>
                        {{ selectedLink('admin.v1.teams.create') }}
                    </li>
                @endif
            </ul>
        </div>
        {{--Designations--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#designations" data-toggle="collapse">Designations</a>
            </div>
            <ul class="list-group collapse {{ panelActive('designations') }} " role="tabpanel" id="designations">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.designations.index') }}">View all designations</a>
                    {{ selectedLink('admin.v1.designations.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.designations.create') }}">Add new designation</a>
                    {{ selectedLink('admin.v1.designations.create') }}
                </li>
            </ul>
        </div>
        {{--Customers--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#customers" data-toggle="collapse">Customers</a>
            </div>
            <ul class="list-group collapse {{ panelActive('customers') }} " role="tabpanel" id="customers">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.customers.index') }}">View all customers</a>
                    {{ selectedLink('admin.v1.customers.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.customers.create') }}">Add new customer</a>
                    {{ selectedLink('admin.v1.customers.create') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.customers.bulk.create') }}">Bulk upload customers</a>
                    {{ selectedLink('admin.v1.customers.bulk.create') }}
                </li>
            </ul>
        </div>

        <h4>Feedback</h4>
        {{--Feedback Category--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#feedback" data-toggle="collapse">Category</a>
            </div>
            <ul class="list-group collapse {{ panelActive('category') }} " role="tabpanel" id="feedback">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.feedback.category.index') }}">View all feedback category</a>
                    {{ selectedLink('admin.v1.feedback.category.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.feedback.category.create') }}">Add new feedback category</a>
                    {{ selectedLink('admin.v1.feedback.category.create') }}
                </li>
            </ul>
            <div class="panel-heading">
                <a href="#question" data-toggle="collapse">Questions</a>
            </div>
            <ul class="list-group collapse {{ panelActive('question') }} " role="tabpanel" id="question">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.feedback.question.index') }}">View all feedback questions</a>
                    {{ selectedLink('admin.v1.feedback.question.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.feedback.question.create') }}">Add new feedback question</a>
                    {{ selectedLink('admin.v1.feedback.question.create') }}
                </li>
            </ul>
        </div>

        <h4>Logs</h4>
        {{--Audit Logs--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#audits" data-toggle="collapse">Logs</a>
            </div>
            <ul class="list-group collapse {{ panelActive('audits') }} " role="tabpanel" id="audits">
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.audits.index') }}">Audit logs</a>
                    {{ selectedLink('admin.v1.audits.index') }}
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.v1.audits.logins') }}">Logins</a>
                    {{ selectedLink('admin.v1.audits.logins') }}
                </li>
            </ul>
        </div>
</div>
