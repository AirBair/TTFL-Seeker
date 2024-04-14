import { type ApiResource } from './ApiResource'

export interface NbaTeam extends ApiResource {
    city?: string | null
    nickname?: string | null
    fullName?: string | null
    tricode?: string | null
    logoFilePath?: string | null
    primaryColor?: string | null
    conference?: string | null
    division?: string | null
    updatedAt?: string | null
}
