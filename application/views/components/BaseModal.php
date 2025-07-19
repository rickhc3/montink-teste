<script>
const BaseModal = {
    name: 'BaseModal',
    props: {
        modalId: {
            type: String,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        modalSize: {
            type: String,
            default: ''
        }
    },
    template: `
        <div class="modal fade" :id="modalId" tabindex="-1" :aria-labelledby="modalId + 'Label'" aria-hidden="true">
            <div class="modal-dialog" :class="modalSize">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" :id="modalId + 'Label'">{{ title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <slot></slot>
                    </div>
                    <div class="modal-footer" v-if="$slots.footer">
                        <slot name="footer"></slot>
                    </div>
                </div>
            </div>
        </div>
    `,
    methods: {
        show() {
            const modal = new bootstrap.Modal(document.getElementById(this.modalId));
            modal.show();
        },
        hide() {
            const modal = bootstrap.Modal.getInstance(document.getElementById(this.modalId));
            if (modal) {
                modal.hide();
            }
        }
    }
};
</script> 