export default interface DataTableOptionsInterface {
    itemsPerPage: number;
    page: number;
    sortBy: string[];
    sortDesc: boolean[];
    groupBy?: string[];
    groupDesc?: boolean[];
}
