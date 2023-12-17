import Helpers from '../helpers'
const apiUrl = import.meta.env.VITE_API_URL
const appKey = import.meta.env.VITE_APP_KEY
const api = (path) => {return import.meta.env.VITE_API_URL+path}
const apiPrefix = '/api/v1'
const errorCodes = {
    400: 'درخواست اشتباه است',
    401: 'لطفا وارد حساب کاربری خود شوید',
    402: 'نیازمند پرداخت',
    403: 'دسترسی غیرمجاز',
    404: 'یافت نشد',
    405: 'روش دسترسی معتبر نیست',
    406: 'غیرقابل قبول',
    407: 'نیاز به مجوز دسترسی پروکسی',
    408: 'مدت زمان ارسال درخواست پایان رسید',
    409: 'خطای تداخل',
    410: 'سرویس از دست رفته',
    411: 'طول درخواست نامعتبر است',
    412: 'Precondition Failed',
    413: 'Payload Too Large',
    414: 'URI Too Long',
    415: 'Unsupported Media Type',
    416: 'Range Not Satisfiable',
    417: 'Expectation Failed',
    418: 'I`m a teapot',
    422: 'Unprocessable Entity',
    425: 'Too Early',
    426: 'Upgrade Required',
    428: 'Precondition Required',
    429: 'Too Many Requests',
    431: 'Request Header Fields Too Large',
    451: 'Unavailable For Legal Reasons',
    500: 'خطای داخلی سرور',
    501: 'Not Implemented',
    502: 'درگاه اشتباه است',
    503: 'سرویس در دسترس نیست',
    504: 'پاسخ مناسبی از سرور دریافت نشد',
    505: 'HTTP Version Not Supported',
    506: 'Variant Also Negotiates',
    507: 'Insufficient Storage',
    508: 'Loop Detected',
    510: 'Not Extended',
    511: 'Network Authentication Required',
}
export default {
    errorHandling(error) {
        const errOptions = {
            timeout: 1000000,
        }
        console.log(error.code)
        if (error.code === 'ECONNABORTED') {
            Helpers.notify('خطا!', 'درخواست ارسالی با پاسخی از سرور مواجه نشد!', {type: 'error'})
        } else if (error.response) {
            Helpers.notify('خطای '+(error?.response?.status || error.code), error?.response?.data?.message || errorCodes[error?.code] || error?.message || 'بروز خطای ناشناخته!', {type: 'error'})
        } else if (error.request) {
            Helpers.notify('خطای '+(error?.response?.status || error.code), 'خطا در ارسال درخواست به سرور. ارتباط اینترنت خود را بررسی کنید.', {type: 'error'})
        }

        return false
    },

    api(path) {
        return api(path);
    },

    // Acquire user`s token from localStorage and returns it directly
    token() {
        const crmState = localStorage?.crmState
        if (crmState) {
            const crmStateJSON = JSON.parse(crmState)
            return crmStateJSON?.userData?.token || ''
        }
    },

    // Create an object to set Axios Headers
    tokenHeader(extraOptions = {}) {
        return {
            headers: {
                Authorization: 'Bearer ' + this.token(),
                ...(extraOptions != {} && extraOptions)
            }
        }
    },

    // A silly function to create FormData more easily
    makeFormData(requestData = {}, isJson = true) {
        if (isJson) {
            return requestData
        }
        let formData = new FormData()
        for (const key in requestData) {
            formData.append(key, requestData[key])
        }

        return formData
    },

    // A function to create userData object from API response directly
    makeUserData(data, token) {
        let userData = {
            username: data?.username,
            email   : data?.email,
            mobile  : data?.mobile,
            role    : data?.role,
            avatar  : data?.details?.avatar ?? null,
            fName   : data?.details?.fName ?? '',
            lName   : data?.details?.lName ?? '',
            dName   : data?.details?.display_name ?? '',
            nCode   : data?.details?.nCode ?? '',
            credit  : data?.details?.credit ?? 0,
            grade   : data?.details?.grade ?? null,
            note    : data?.details?.note ?? '',
            token   : token ?? '',
        }
        const t = userData.fName+' '+userData.lName
        userData['dName'] = t.trim().length > 0 ? t : userData.username
        return userData
    },

    async login(username, password) {
        const res = await axios.post(route('auth.login'), this.makeFormData({username, password}))
        let response = {
            status: false,
            userData: null,
            message: 'نام کاربری یا کلمه عبور اشتباه است.'
        }
        if (res?.data?.status) {
            response = {
                status: true,
                userData: this.makeUserData(res.data?.result, res.data?.token),
                message: res?.data?.message || ''
            }
        }
        return response
    },

    async getFormOptions() {
        const res = await axios.get(route('api.forms.getOptions'), this.tokenHeader())
        return res?.data
    },

    async getFormsList() {
        const res = await axios.get(route('api.forms.list'), this.tokenHeader())
        return res?.data
    },

    async changeFormStatus(form_id) {
        const res = await axios.post(route('api.forms.changeStatus'), { id: form_id }, this.tokenHeader())
        return res?.data
    },

    async saveForm(form) {
        const res = await axios.post(route('api.forms.save'), this.makeFormData(form), this.tokenHeader())
        return res?.data
    },

    async usedFormCount(form_id) {
        const res = await axios.post(route('api.forms.usedFormCount'), this.makeFormData({id: form_id}), this.tokenHeader())
        return res?.data
    },

    async deleteForm(form_id) {
        const res = await axios.post(route('api.forms.delete'), this.makeFormData({id: form_id}), this.tokenHeader())
        return res?.data
    },

    async deleteField(field_id, form_id) {
        const res = await axios.post(route('api.forms.deleteField'), this.makeFormData({field_id, form_id}), this.tokenHeader())
        return res?.data
    },

    async getFormDetails(form_id) {
        const res = await axios.post(route('api.forms.getFormDetails'), this.makeFormData({id: form_id}))
        return res?.data
    },

    async saveFormData(form_data) {
        const res = await axios.post(route('api.forms.saveFormData'), form_data, {headers: {'Content-Type': 'multipart/form-data' }})
        return res?.data
    },

    async getFormInbox(form_id) {
        const res = await axios.post(route('api.forms.getFormInbox'), this.makeFormData({form_id}), this.tokenHeader())
        return res?.data
    },

    async getFormInboxMore(form_id, last_inbox) {
        const res = await axios.post(route('api.forms.getFormInboxMore'), this.makeFormData({form_id, last_inbox}), this.tokenHeader())
        return res?.data
    },

    async editFormStatus(form_id, status, comment) {
        const res = await axios.post(route('api.forms.editFormStatus'), this.makeFormData({form_id, status, comment}), this.tokenHeader())
        return res?.data
    },

    async trackForm(transaction_id, form_id=null) {
        const res = await axios.post(route('api.forms.trackForm'), this.makeFormData({track_id: transaction_id, form: form_id}))
        return res?.data
    },

    async verifyInvoice(authority_id) {
        const res = await axios.post(route('api.invoices.verify'), this.makeFormData({transaction: authority_id}))
        return res?.data
    },

    async getUserRoles() {
        const res = await axios.post(route('api.permissions.getMainList'), {}, this.tokenHeader())
        return res.data
    },

    async getSettingsData() {
        const res = await axios.post(route('api.settings.getValues'), {}, this.tokenHeader())
        return res?.data
    },

    async setSettingsData(values) {
        const res = await axios.post(route('api.settings.setValues'), this.makeFormData(values), this.tokenHeader())
        return res?.data
    },

    async getCategoriesList() {
        const res = await axios.post(route('api.categories.list'), {}, this.tokenHeader())
        return res?.data
    },

    async saveCategory(category) {
        const res = await axios.post(route('api.categories.save'), this.makeFormData(category), this.tokenHeader())
        return res?.data
    },

    async getUsersList() {
        const res = await axios.post(route('api.users.list'), {}, this.tokenHeader())
        return res?.data
    },

    async saveUser(user) {
        const res = await axios.post(route('api.users.save'), this.makeFormData(user), this.tokenHeader())
        return res?.data
    },

    async getUserProfile(user = false, needToken = true) {
        const res = await axios.post(route('api.profile.view'), user ? this.makeFormData({username: user}) : {}, needToken ? this.tokenHeader() : {})
        return res?.data
    },

    async updateProfile(form) {
        const res = await axios.post(route('api.profile.update'), this.makeFormData(form), this.tokenHeader())
        const response = {
            status: res?.data?.status || false,
            userData: this.makeUserData(res.data?.userData, this.token()),
            result: res?.data?.result || {},
            message: res?.data?.message || ''
        }
        return response
    },

    async checkUserStatus(user) {
        const res = await axios.post(route('api.checkUserStatus'), { user: user }, this.tokenHeader())
        return res?.data
    },

    async saveUserRole(formData) {
        const res = await axios.post(route('api.permissions.save'), this.makeFormData(formData), this.tokenHeader())
        return res?.data
    },

    async getContactsList(filters = null) {
        let data = {}
        if (typeof filters == 'object') {
            data = {filters: filters}
        }
        const res = await axios.post(route('api.contacts.getContactsList'), data, this.tokenHeader())
        return res?.data
    },

    async getContactsDetails() {
        const res = await axios.post(route('api.contacts.getContactsExtData'), {}, this.tokenHeader())
        return res?.data
    },

    async saveContact(form) {
        const res = await axios.post(route('api.contacts.saveContact'), form, this.tokenHeader())
        return res?.data
    },

    async deleteContact(id) {
        const res = await axios.post(route('api.contacts.deleteContact'), {id: id}, this.tokenHeader())
        return res?.data
    },

    async getSingleContact(id) {
        const res = await axios.post(route('api.contacts.getSingleContact'), {id: id}, this.tokenHeader())
        return res?.data
    },

    async getContactTags() {
        const res = await axios.post(route('api.contact-tags.getSingleContact'), this.tokenHeader())
        return res?.data
    },

    async saveContactTags(form) {
        const res = await axios.post(route('api.contact-tags.saveContactTag'), {...form}, this.tokenHeader())
        return res?.data
    },

    async deleteContactTags(tag_id) {
        const res = await axios.post(route('api.contact-tags.deleteContactTag'), {tag_id}, this.tokenHeader())
        return res?.data
    },

    async setContactTag(contact_id, tag_id) {
        const res = await axios.post(route('api.contacts.setContactTag'), {contact_id, tag_id}, this.tokenHeader())
        return res?.data
    },

    async getPositionsList() {
        const res = await axios.post(route('api.contact-positions.getPositionsList'), {}, this.tokenHeader())
        return res?.data
    },

    async savePosition(form) {
        const res = await axios.post(route('api.contact-positions.savePosition'), {...form}, this.tokenHeader())
        return res?.data
    },

    async deletePosition(position_id) {
        const res = await axios.post(route('api.contact-positions.deletePosition'), {position_id}, this.tokenHeader())
        return res?.data
    },

    async getProjectStatusList() {
        const res = await axios.post(route('api.project-statuses.getProjectStatusList'), {}, this.tokenHeader())
        return res?.data
    },

    async saveProjectStatus(form) {
        const res = await axios.post(route('api.project-statuses.saveProjectStatus'), {...form}, this.tokenHeader())
        return res?.data
    },

    async deleteProjectStatus(status_id) {
        const res = await axios.post(route('api.project-statuses.deleteProjectStatus'), {status_id}, this.tokenHeader())
        return res?.data
    },

    async setProjectStatusAsDefault(status_id) {
        const res = await axios.post(route('api.project-statuses.setProjectStatusAsDefault'), {status_id}, this.tokenHeader())
        return res?.data
    },

    async getProjectTypeList() {
        const res = await axios.post(route('api.project-types.getProjectTypeList'), {}, this.tokenHeader())
        return res?.data
    },

    async saveProjectType(form) {
        const res = await axios.post(route('api.project-types.saveProjectType'), {...form}, this.tokenHeader())
        return res?.data
    },

    async deleteProjectType(type_id) {
        const res = await axios.post(route('api.project-types.deleteProjectType'), {type_id}, this.tokenHeader())
        return res?.data
    },

    async getProjectResultList() {
        const res = await axios.post(route('api.project-results.getProjectResultList'), {}, this.tokenHeader())
        return res?.data
    },

    async saveProjectResult(form) {
        const res = await axios.post(route('api.project-results.saveProjectResult'), {...form}, this.tokenHeader())
        return res?.data
    },

    async deleteProjectResult(result_id) {
        const res = await axios.post(route('api.project-results.deleteProjectResult'), {result_id}, this.tokenHeader())
        return res?.data
    },

    async setProjectResultAsDefault(result_id) {
        const res = await axios.post(route('api.project-results.setProjectResultAsDefault'), {result_id}, this.tokenHeader())
        return res?.data
    },

    async reverseGeocode(coords) {
        const res = await axios.get("https://nominatim.openstreetmap.org/reverse?zoom=18&format=json&accept-language=fa&lon=" + coords[0] + "&lat=" + coords[1])
        return res?.data
    },

    async getProjectsList(filters = null, rows = 12) {
        const res = await axios.post(route('api.projects.getProjectsList'), {filters: filters, rows: rows}, this.tokenHeader())
        return res?.data
    },

    async getSingleProject(project_id) {
        const res = await axios.post(route('api.projects.getSingleProject'), {project: project_id}, this.tokenHeader())
        return res?.data
    },

    async getProjectsExtData() {
        const res = await axios.post(route('api.projects.getProjectsExtData'), {}, this.tokenHeader())
        return res?.data
    },

    async saveProject(form) {
        const headers = this.tokenHeader({'Content-Type': 'multipart/form-data'})
        const axiosParams = {
            url: route('api.projects.saveProject'),
            method: 'post',
            data: form,
            ...headers,
        }
        console.log(axiosParams)
        debugger
        const res = await axios(axiosParams)
        // const res = await axios.post(route('api.projects.saveProject'), form, this.tokenHeader({'Content-Type': 'multipart/form-data'}))
        return res?.data
    },

    async addProjectAttachment(form, _callback = null) {
        let headers = this.tokenHeader()
        headers['Content-Type'] = 'multipart/form-data'
        if (_callback) {
            headers['onUploadProgress'] = _callback
        }
        const res = await axios.post(route('api.projects.addProjectAttachment'), form, headers)
        return res?.data
    },

    async addProjectComment(form) {
        const res = await axios.post(route('api.projects.addProjectComment'), this.makeFormData(form), this.tokenHeader())
        return res?.data
    },

    async addProjectNote(form) {
        const res = await axios.post(route('api.projects.addProjectNote'), this.makeFormData(form), this.tokenHeader())
        return res?.data
    },

    async renameAlt(form) {
        const res = await axios.post(route('api.images.renameAlt'), this.makeFormData(form), this.tokenHeader())
        return res?.data
    },

    async deleteImage(form) {
        const res = await axios.post(route('api.images.deleteImage'), this.makeFormData(form), this.tokenHeader())
        return res?.data
    },

    async downloadFile(file) {
        let headers = {
            headers: {
                Authorization: 'Bearer ' + this.token()
            },
            responseType: 'blob',
            params: {
                file: file
            }
        }
        const res = await axios.get(route('api.images.downloadFile'), headers)
        return res?.data
    },

    async getTodoList() {
        const res = await axios.post(route('api.todos.getTodoList'), {}, this.tokenHeader())
        return res?.data
    },

    async saveTodo(form) {
        const res = await axios.post(route('api.todos.saveTodo'), this.makeFormData(form), this.tokenHeader())
        return res?.data
    },

    async deleteTodo(todo_id) {
        const res = await axios.post(route('api.todos.deleteTodo'), {todo: todo_id}, this.tokenHeader())
        return res?.data
    },

    async changeAlarmStatus(alarm_id) {
        const res = await axios.post(route('api.alarm.changeAlarmStatus'), {alarm: alarm_id}, this.tokenHeader())
        return res?.data
    },
}
