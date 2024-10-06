export interface ApiError {
    title: string
    description: string
}

export interface ApiViolation {
    propertyPath: string
    message: string
    code: string
}

export interface ApiConstraintViolationList extends ApiError {
    violations: ApiViolation[]
}
