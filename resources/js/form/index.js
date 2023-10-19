export default class form {
    constructor(fields) {
        let preparedFields = []
        let tab_index = 1
        for (const key in fields) {
            let field = fields[key]
            if (typeof field !== 'object') {
                field = {
                    name: field
                }
            }
            preparedFields[key] = {
                ...(field?.id) && {id: field?.id},
                ...(field?.field_id) && {id: field?.field_id},
                ...(field?.form_id) && {id: field?.form_id},
                name: field?.name || key,
                title: field?.title || field?.name,
                hint: field?.hint || null,
                description: field?.description || null,
                placeholder: field?.placeholder || null,
                type: field?.type || 1,
                default: field?.default || null,
                value: field?.default || null,
                is_required: field?.is_required || false,
                min: field?.min || null,
                max: field?.max || null,
                max_rows: field?.max_rows || null,
                steps: field?.steps || null,
                options: field?.options || null,
                tab_index: field?.tab_index || tab_index,
                custom_class: field?.custom_class || null,
                custom_id: field?.custom_id || null,
                col_mobile: field?.col_mobile || 12,
                col_tablet: field?.col_tablet || 6,
                col_desktop: field?.col_desktop || 4,
                col_widescreen: field?.col_widescreen || 3,
                validator: field?.validator || null,
            }
            tab_index++
        }

        return preparedFields
    }
}
