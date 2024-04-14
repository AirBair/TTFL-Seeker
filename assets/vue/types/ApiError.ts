export interface ApiError {
    'hydra:title': string
    'hydra:description': string
}

export interface ApiViolation {
    propertyPath: string
    message: string
    code: string
}

export interface ApiConstraintViolationList extends ApiError {
    violations: ApiViolation[]
}
