import AbstractResource from './AbstractResource'

export default class NbaTeam extends AbstractResource {
    city?: string
    nickname?: string
    fullName?: string
    tricode?: string
    logoFilePath?: string
    primaryColor?: string
    conference?: string
    division?: string
    updatedAt?: string
}
