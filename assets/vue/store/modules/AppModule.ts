import { VuexModule, Module, Mutation } from 'vuex-module-decorators'

export interface AppState {
    appName: string;
    isOpenSidebarDrawer: boolean;
}

@Module({
    namespaced: false,
    name: 'app'
})
export default class AppModule extends VuexModule implements AppState {
    appName = 'TTFL Seeker'
    isOpenSidebarDrawer = true

    @Mutation
    toggleSidebarDrawer (): void {
        this.isOpenSidebarDrawer = !this.isOpenSidebarDrawer
    }
}
