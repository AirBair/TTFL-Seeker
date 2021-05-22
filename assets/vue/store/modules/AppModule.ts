import { VuexModule, Module, Mutation } from 'vuex-module-decorators'

export interface AppState {
    appName: string;
    isOpenSidebarDrawer: boolean;
    nbaYear: number
    isNbaPlayoffs: boolean
}

@Module({
    namespaced: false,
    name: 'app'
})
export default class AppModule extends VuexModule implements AppState {
    appName = 'TTFL Seeker'
    isOpenSidebarDrawer = true
    nbaYear = new Date().getFullYear()
    isNbaPlayoffs = false

    @Mutation
    toggleSidebarDrawer (): void {
        this.isOpenSidebarDrawer = !this.isOpenSidebarDrawer
    }

    @Mutation
    setIsOpenSidebarDrawer (isOpenSidebarDrawer: boolean): void {
        this.isOpenSidebarDrawer = isOpenSidebarDrawer
    }

    @Mutation
    setNbaYear (nbaYear: number): void {
        this.nbaYear = nbaYear
    }

    @Mutation
    setIsNbaPlayoff (isNbaPlayoffs: boolean): void {
        this.isNbaPlayoffs = isNbaPlayoffs
    }
}
