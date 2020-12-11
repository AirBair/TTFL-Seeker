import AbstractResource from './AbstractResource'

export default class NbaTeam extends AbstractResource {
    city?: string
    nickname?: string
    fullName?: string
    tricode?: string
    conference?: string
    division?: string
    updatedAt?: string
}
