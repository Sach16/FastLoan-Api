import Messages from './common/messages'
import Navigator from './common/navigator'
import Forms from './common/forms'
import Campaigns from './components/campaigns'
import Addresses from './components/addresses'
import Projects from './components/projects'
import Dates from './common/dates'
import Teams from './components/teams'
import Leads from './components/leads'
import Loans from './components/loans'
import States from './components/states'
import ProjectBanks from './components/projectbanks'
import Banks from './components/banks'

// Initialize Messages
var messages = new Messages
messages.flash()
messages.confirm()
messages.uploading()
messages.popover()

// Initialize Navigator
var navigator = new Navigator
navigator.back()

// Initialize forms
var forms = new Forms
forms.autoSubmit();

// Initialize Campaigns
var campaigns = new Campaigns
campaigns.autocomplete()

// Initialize Addresses
var addresses = new Addresses
addresses.city()

// Initialize Projects
var projects = new Projects
projects.owners()
projects.DSAowners()
projects.builders()

// Initialize Dates
var dates = new Dates
dates.picker()
dates.months()
dates.time()
dates.datetime()
dates.dobDate()

// Initialize Teams
var teams = new Teams
teams.nonmembers()


var leads = new Leads
leads.autocomplete()


// Initialize Loans
var loans = new Loans
loans.autocomplete()

// Initialize States
var states = new States
states.autocomplete()

var projectBanks = new ProjectBanks()
states.states()

// Initialize Banks
var banks = new Banks
banks.bank()
