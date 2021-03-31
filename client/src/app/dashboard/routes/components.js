
export const Main = r => require.ensure([], () => r(require('../components/main')), 'dashboard-bundle')

export const Search = r => require.ensure([], () => r(require('../components/forms/search')), 'dashboard-bundle')

export const Index = r => require.ensure([], () => r(require('../components/forms/index')), 'dashboard-bundle')

export const Missions = r => require.ensure([], () => r(require('../components/forms/missions')), 'dashboard-bundle')

export const Agenda = r => require.ensure([], () => r(require('../components/forms/agenda')), 'dashboard-bundle')

export const ProfileEdit = r => require.ensure([], () => r(require('../components/forms/profile_edit')), 'dashboard-bundle')

export const Assistance = r => require.ensure([], () => r(require('../components/forms/assistance')), 'dashboard-bundle')

export const SingleOffer = r => require.ensure([], () => r(require('../components/forms/SingleOffer')), 'dashboard-bundle')

export const CvIframe = r => require.ensure([], () => r(require('../components/forms/CvIframe')), 'dashboard-bundle')

export const SelectToOffer = r => require.ensure([], () => r(require('../components/forms/SelectToOffer')), 'dashboard-bundle')

export const AddUser = r => require.ensure([], () => r(require('../components/forms/AddUser')), 'dashboard-bundle')

export const UpdateClient = r => require.ensure([], () => r(require('../components/forms/UpdateClient')), 'dashboard-bundle')
