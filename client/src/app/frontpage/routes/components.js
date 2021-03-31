export const Main = r => require.ensure([], () => r(require('../components/main')), 'frontpage-bundle')

export const Index = r => require.ensure([], () => r(require('../components/forms/index')), 'frontpage-bundle')

export const Search = r => require.ensure([], () => r(require('../components/forms/search')), 'frontpage-bundle')
