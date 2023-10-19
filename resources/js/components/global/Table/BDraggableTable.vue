<template>
    <div class="table-container w-100" :style="overflow ? 'overflow: visible' : ''">
        <table :class="{ 'is-hoverable': hoverable, 'is-striped': striped }" class="table mb-0" v-bind="$attrs">
            <thead :class="tableHeaderClass">
                <tr :class="trClass">
                    <th v-for="(cell, index) in tableHeaders"
                        :key="'_'+index+'tableHeadCell'+cell.id"
                        :class="cell.thClass+' '+(cell?.sortable ? 'is-clickable' : '')"
                        :style="cell.thStyle"
                        @click="setSortingMethod(cell.id, cell?.sortable === true)"
                    >
                        <i class="fas sortIndicator" :class="(currentSortField===cell.id ? 'activeSort' : '')+' '+(currentSortField===cell.id ? (currentSortDir==='DESC' ? 'fa-caret-circle-up' : 'fa-caret-circle-down') : 'fa-caret-down')" v-if="cell?.sortable === true"></i>
                        {{ cell.title }}
                    </th>
                </tr>
            </thead>
            <tbody :class="tableBodyClass" v-if="!isBusy"
                   @dragenter.prevent
                   @dragover.prevent
            >
                <slot name="row" :item="row">
                    <tr
                        v-for="(row, index) in computedData"
                        :key="'smartTable'+index+(id !== null ? id : '')+row.id"
                        :class="trClass"
                        @click="checkLink(row?.link)"
                        :draggable="draggable"
                        @dragstart="dragging($event, row)"
                        @drop="dropped($event, row)"
                        @dragenter.prevent
                        @dragover.prevent
                    >
                        <td v-for="cell in headers"
                            :key="'smartTable'+index+(id !== null ? id : '')+row.id+'cell'"
                            :class="cell.tdClass"
                            :style="cell.tdStyle"
                        >
                            <slot :name="cell.id" :item="{index, ...row}" :value="row[cell.id]">
                                {{ row[cell.id] }}
                            </slot>
                        </td>
                    </tr>
                </slot>
                <tr v-if="computedData?.length <= 0 && !isBusy">
                    <td :colspan="tableHeaders?.length || 1">
                        <slot name="empty">
                            <div class="text-center text-danger my-2">
                                <i class="fas fa-exclamation-triangle is-warning mr-3"></i>
                                <strong>اطلاعاتی یافت نشد</strong>
                            </div>
                        </slot>
                    </td>
                </tr>
            </tbody>
            <tbody v-if="isBusy">
                <slot name="busy">
                    <tr v-for="n in 5">
                        <td v-for="(cell, index) in tableHeaders"
                            :key="'_'+index+'loader_'+cell.id"
                            class="loading"
                        >
                            <div class="flex-table-cell" data-th="Location">
                                <div class="content-shape is-grow-1 mx-1 loads"></div>
                            </div>
                        </td>
                    </tr>
                </slot>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "BDraggableTable",
    props: {
        id: {
            default: null
        },
        key: [String],
        tableClass: {
            type: String,
            default: ''
        },
        tableStyle: {
            default: null
        },
        trClass: {
            type: String,
            default: ''
        },
        tableHeaderClass: {
            type: String,
            default: ''
        },
        tableBodyClass: {
            type: String,
            default: ''
        },
        searchField: {
            type: [Boolean, String],
            default: false
        },
        headers: {
            default: []
        },
        hasLink: {
            type: Boolean,
            default: false
        },
        data: {
            default: []
        },
        defaultSort: {
            default: null,
        },
        defaultSortDirection: {
            default: 'ASC', // 'DESC'
        },
        perPage: {
            type: Number,
            default: 10
        },
        startingPage: {
            type: Number,
            default: 1
        },
        isBusy: {
            type: Boolean,
            default: false
        },
        striped: Boolean,
        hoverable: Boolean,
        responsive: Boolean,
        overflow: Boolean,
        draggable: Boolean,
    },
    data() {
        return {
            tableHeaders: [],
            currentSortField: this.defaultSort,
            currentSortDir: this.defaultSortDirection,
            currentPage: this.startingPage,
            tableData: []
        }
    },
    created() {
        this.tableData = this.data
        let i
        for (i = 0; i < this.headers.length; i++) {
            if (typeof this.headers[i] == 'string') {
                this.tableHeaders.push({
                    id: i,
                    title: this.headers[i],
                    thClass: null,
                    thStyle: null,
                    tdClass: null,
                    tdStyle: null,
                    sortable: true,
                    hidden: false,
                })
            } else {
                this.tableHeaders.push({
                    id: this.headers[i]?.id || i,
                    title: this.headers[i].title,
                    thClass: this.headers[i]?.thClass || null,
                    thStyle: this.headers[i]?.thStyle || null,
                    tdClass: this.headers[i]?.tdClass || null,
                    tdStyle: this.headers[i]?.tdStyle || null,
                    sortable: this.headers[i]?.sortable === true,
                    hidden: this.headers[i]?.hidden === true,
                })
            }
        }
    },
    mounted() {
        this.update()
    },
    watch: {
    },
    computed: {
        computedData() {
            let table = this.tableData

            if (this.searchField !== false && this?.searchField?.length > 0) {
                table = table.filter(item => {
                    let found = false
                    for (const itemKey in item) {
                        const temp = typeof item[itemKey] === 'string' ? item[itemKey] : (item[itemKey] ? item[itemKey].toString() : '')
                        if (temp.includes(this.searchValue)) {
                            found = true
                        }
                    }
                    return found
                })
            }

            return table?.sort((a, b) => {
                if (this.currentSortDir === 'ASC') {
                    return a[this.currentSortField] > b[this.currentSortField] ? 1 : -1
                } else {
                    return b[this.currentSortField] > a[this.currentSortField] ? 1 : -1
                }
            })
        }
    },
    methods: {
        setSortingMethod(id, isSortable) {
            if (!isSortable) {
                return
            }
            if (this.currentSortField === id) {
                this.currentSortDir = this.currentSortDir === 'ASC' ? 'DESC' : 'ASC'
            } else {
                this.currentSortField = id
            }
        },
        checkLink(link) {
            if (this.hasLink && link != undefined && link.length) {
                this.$router.push(link)
            }
        },
        update() {
            this.$nextTick(e => {
                this.$forceUpdate()
            })
        },
        dragging(e, item) {
            e.dataTransfer.dropEffect = 'move'
            e.dataTransfer.effectAllowed = 'move'
            e.dataTransfer.setData('itemId', item.id)
        },
        dropped(e, row) {
            const itemId = e.dataTransfer.getData('itemId')
            let item = this.tableData.find((item) => item.id == itemId)
            item.tab_index = row.tab_index
            this.tableData.sort(function(a, b) {
                if (a.tab_index < b.tab_index) return -1;
                if (a.tab_index > b.tab_index) return 1;
                return 0;
            });
            for (let i = 0; i < this.tableData.length; i++) {
                this.tableData[i].tab_index = i+1
            }
        },
    },
};
</script>

<style scoped>
.sortIndicator {
    width: 16px;
    color: rgba(180, 180, 180, 0.55)
}
.sortIndicator.activeSort {
    color: rgba(65, 65, 65, 0.75)
}
.sortIndicator.desc:not(.fal) {
    transform: rotate(180deg);
}
.titleBar {
    display: inline-block;
    white-space: nowrap;
    overflow: hidden !important;
    text-overflow: ellipsis;
}
tbody tr td.loading {
    padding-top: 1.35rem;
    padding-bottom: 1.35rem;
}
tbody.has-slimscroll {
    max-height: 400px;
}
</style>
