// ==================================================
// Admin`s Section Components
import Preview                  from "../components/pages/WebHome/FormPreview.vue";
import Login                    from "../components/pages/Auth/LoginComponent.vue";
import AdminDashboard           from "../components/pages/AdminDashboard/AdminDashboardComponent.vue";
import AdminFormBuilder         from "../components/pages/AdminFormBuilder/AdminFormBuilderComponent.vue";
import AdminFormInbox           from "../components/pages/AdminFormInbox/AdminFormInboxComponent.vue";
import AdminProductCategory     from '../components/pages/AdminShop/AdminProductCategoryComponent.vue';
import AdminUnderConstruction   from "../components/pages/AdminUnderConstruction/AdminUnderConstructionComponent.vue";
import AdminFileManager         from "../components/pages/AdminFileManager/AdminFileManagerComponent.vue";
import AdminSettings            from "../components/pages/AdminSettings/AdminSettingsComponent.vue";
import AdminContacts            from "../components/pages/AdminContacts/AdminContactsComponent.vue";
import AdminContactPositions    from "../components/pages/AdminContactPositions/AdminContactPositionsComponent.vue";
import AdminProjectStatus       from "../components/pages/AdminProjectStatus/AdminProjectStatusComponent.vue";
import AdminProjectType         from "../components/pages/AdminProjectType/AdminProjectTypeComponent.vue";
import AdminProjectResult       from "../components/pages/AdminProjectResult/AdminProjectResultComponent.vue";
import AdminProjects            from "../components/pages/AdminProject/AdminProjectComponent.vue";
import AdminProject             from "../components/pages/AdminProject/AdminProjectComponent.vue";
import AdminUsers               from "../components/pages/AdminUsers/AdminUsersComponent.vue";
import AdminRoles               from "../components/pages/AdminUsers/AdminRolesComponent.vue";
import UserProfileEdit          from "../components/pages/Profile/UserProfileEditComponent.vue";
// ==================================================

// const adminRoutePrefix = (import.meta?.env?.VITE_ADMIN_ROUTE_PREFIX && import.meta?.env?.VITE_ADMIN_ROUTE_PREFIX.length) ? '/'+import.meta.env.VITE_ADMIN_ROUTE_PREFIX : ''

const adminRoutes = [
    {
        path: '/dashboard',
        component: AdminDashboard,
        name: 'admin.dashboard',
        meta: {
            pageTitle: 'پیشخوان مدیریت',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/',
        redirect: {name: 'admin.dashboard'},
        meta: {
            middleware: 'AUTH'
        }
    },
    {
        path: '/login',
        component: Login,
        name: 'login',
        meta: {
            pageTitle: 'Login',
            breadcrumbs: [],
            middleware: 'WEB'
        }
    },
    {
        path: '/under-construction',
        name: 'admin.under-construction',
        component: AdminUnderConstruction,
        meta: {
            pageTitle: 'در دست ساخت',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/form-builder',
        component: AdminFormBuilder,
        name: 'admin.formBuilder',
        meta: {
            pageTitle: 'فرم‌ساز',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/form-inbox',
        component: AdminFormInbox,
        name: 'admin.formInbox',
        meta: {
            pageTitle: 'صندوق ورودی فرم‌ها',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/contacts',
        component: AdminContacts,
        name: 'admin.contacts',
        meta: {
            pageTitle: 'اشخاص و مخاطبین',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/base/contact-positions',
        component: AdminContactPositions,
        name: 'admin.contact-positions',
        meta: {
            pageTitle: 'جایگاه اشخاص',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/base/project-statuses',
        component: AdminProjectStatus,
        name: 'admin.project-statuses',
        meta: {
            pageTitle: 'وضعیت‌های پروژه',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/base/project-types',
        component: AdminProjectType,
        name: 'admin.project-types',
        meta: {
            pageTitle: 'انواع پروژه',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/base/project-results',
        component: AdminProjectResult,
        name: 'admin.project-results',
        meta: {
            pageTitle: 'نتایج پروژه',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/base/categories',
        component: AdminProductCategory,
        name: 'admin.shop.categories',
        meta: {
            pageTitle: 'دسته‌بندی کالا',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/projects',
        component: AdminProjects,
        name: 'admin.projects',
        props: false,
        params: {project_id: null},
        meta: {
            pageTitle: 'پروژه‌ها',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/project/:project_id',
        component: AdminProject,
        name: 'admin.project.single',
        meta: {
            pageTitle: 'پروژه‌ها',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/users',
        component: AdminUsers,
        name: 'admin.users',
        meta: {
            pageTitle: 'کاربران',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/users/permissions',
        component: AdminRoles,
        name: 'admin.permissions',
        meta: {
            pageTitle: 'نقش‌های کاربری',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/file-manager',
        component: AdminFileManager,
        name: 'admin.filemanager',
        meta: {
            pageTitle: 'مدیریت فایل‌ها',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/profile',
        component: UserProfileEdit,
        name: 'admin.profile',
        props: { layout: 'AdminLayoutComponent', changeable: true, username: false },
        meta: {
            pageTitle: 'پروفایل',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
    {
        path: '/settings',
        component: AdminSettings,
        name: 'admin.settings',
        meta: {
            pageTitle: 'تنظیمات سیستم',
            breadcrumbs: [],
            middleware: 'AUTH'
        }
    },
]

export default adminRoutes
