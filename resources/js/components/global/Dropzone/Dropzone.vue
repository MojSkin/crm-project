<template>
    <div class="main">
        <div
            class="dropzone-container is-clickable"
            @dragover="dragover"
            @dragleave="dragleave"
            @drop="drop"
            @click="$refs.file.click()"
        >
            <b-overlay :show="isBusy" loader-text="در حال بارگزاری فایل‌ها... لطفا کمی منتظر بمانید..." loader-custom-class="has-text-info" style="border-radius: 12px">
                <input
                    type="file"
                    multiple
                    name="file"
                    id="fileInput"
                    class="hidden-input"
                    @change="onChange"
                    ref="file"
                    :accept="accept"
                />

                <label for="fileInput" class="file-label">
                    <slot name="release-text">
                        <div v-if="isDragging">{{ releaseText }}</div>
                    </slot>
                    <slot name="dropText">
                        <div v-if="!isDragging">{{ dropText }}</div>
                    </slot>

                </label>

                <div class="preview-container mt-4" v-if="files.length">
                    <div v-for="file in files" :key="file.name" class="preview-card" @click.stop>
                        <div>
                            <img class="preview-img" :src="generateURL(file)" />
                            <div class="overlay">
                                <p>
                                    <bdi>{{ file.name }}</bdi>
                                    <span>{{ $helpers.humanReadableFileSize(Math.round(file.size / 1000)) }}</span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <button @click.stop="remove(files.indexOf(file))">
                                <i class="fal fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </b-overlay>
        </div>
        <div class="progress-wrapper">
            <slot name="progress"/>
        </div>
    </div>
</template>

<script>
export default {
    name: "Dropzone",
    props: {
        modelValue: Array,
        releaseText: {
            type: String,
            default: 'فایل‌های خود را اینجا رها کنید...'
        },
        dropText: {
            type: String,
            default: 'فایل‌های خود را بکشید و اینجا رها کنید یا برای انتخاب فایل مورد نظر، روی این نوشته کلیک کنید...'
        },
        isBusy: Boolean,
        accept: {
            type: [String, Array],
            default: '*.*'
        }
    },
    data() {
        return {
            isDragging: false,
            files: this.modelValue,
        }
    },
    created() {
    },
    mounted() {
    },
    computed: {},
    watch: {
        modelValue() {
            this.files = this.modelValue
        }
    },
    methods: {
        onChange() {
            this.files = [...this.$refs.file.files];
            this.$emit('dropped', this.files)
        },
        dragover(e) {
            e.preventDefault();
            this.isDragging = true;
        },
        dragleave() {
            this.isDragging = false;
        },
        drop(e) {
            e.preventDefault();
            this.$refs.file.files = e.dataTransfer.files;
            this.onChange();
            this.isDragging = false;
        },
        remove(i) {
            this.files.splice(i, 1);
        },
        generateURL(file) {
            let fileSrc = URL.createObjectURL(file);
            setTimeout(() => {
                URL.revokeObjectURL(fileSrc);
            }, 1000);
            return fileSrc;
        },
    }
}
</script>

<style scoped>
.main {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    max-height: 400px;
    justify-content: center;
    text-align: center;
    width: 100%;
}

.progress-wrapper {
    position: absolute;
    bottom: 0px;
    width: 100%;
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
    overflow: hidden;
}

.dropzone-container {
    overflow: hidden;
    width: 100%;
    padding: 4rem;
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
}

.hidden-input {
    opacity: 0;
    overflow: hidden;
    position: absolute;
    width: 1px;
    height: 1px;
}

.file-label {
    font-size: 20px;
    display: block;
    cursor: pointer;
}

.preview-container {
    display: flex;
    padding-top: 2rem;
    column-gap: 15px;
    row-gap: 20px;
    flex-wrap: wrap;
    flex-direction: row;
    overflow-y: scroll;
    padding-left: 20px;
    max-height: 300px;
}

.preview-card {
    position: relative;
    display: flex;
}

.preview-card .overlay {
    position: absolute;
    inset: 0;
    z-index: 2;
    opacity: 0;
    background-color: rgba(255, 255, 255, 0.65);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 100ms ease-in-out;
    overflow: hidden;
}

.preview-card .overlay p {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: #FFF;
    background-color: rgba(15, 29, 56, 0.60);
    width: 100%;
    font-size:12px;
}
.preview-card .overlay p bdi {
    display: block;
    width: calc(100% - 20px);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 700;
}
.preview-card .overlay:hover {
    opacity: 1;
}

.preview-card button {
    background-color: rgba(240, 240, 240, 0.7);
    border: none;
    color: #e62965;
    width: 32px;
    height: 32px;
    padding: 4px 2px 0 2px;
    position: absolute;
    border-radius: 50%;
    left: -12px;
    top: -12px;
    transition: all 200ms ease-in-out;
    box-shadow: 0 0 5px #cfcfcf;
    z-index: 3;
}
.preview-card button:hover {
    background-color: #e62965;
    color: rgba(250, 250, 250, 1);
}

.preview-img {
    width: 120px;
    height: 120px;
    border-radius: 5px;
    border: 1px solid #cfcfcf;
    background-color: #565656;
    object-fit: contain;
}
</style>
