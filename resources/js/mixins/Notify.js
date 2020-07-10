/**
 * Notifications mixin
 */
export default {

    data() {
        return {
            notifyPosition: 'top-right',
        }
    },

    methods: {

        notifySuccess(message, timer = 5000) {

            this.notify({
                icon: 'pe-7s-check',
                message,
                type: 'success',
                timer,
            });
        },

        notifyInfo(message, timer = 10000) {

            this.notify({
                icon: 'pe-7s-info',
                message,
                type: 'info',
                timer,
            });
        },

        notifyWarning(message, timer = 8000) {

            this.notify({
                icon: 'pe-7s-attention',
                message,
                type: 'warning',
                timer,
            });
        },

        notifyError(message, timer = 0) {

            this.notify({
                icon: 'pe-7s-less',
                message,
                type: 'danger',
                timer,
            });
        },

        notify(options) {

            let placementData = this.notifyPlacements(this.notifyPosition);

            $.notify({
                icon: options.icon,
                message: options.message,
    
            },{
                type: options.type,
                timer: options.timer,
                placement: {
                    from: placementData.from,
                    align: placementData.align,
                }
            });
        },

        notifyPlacements(position) {

            switch (position) {
                case 'top-left': {
                    return {
                        from: 'top',
                        aligh: 'left',
                    }
                }
                case 'top-center': {
                    return {
                        from: 'top',
                        aligh: 'center',
                    }
                }
                case 'top-right': {
                    return {
                        from: 'top',
                        aligh: 'right',
                    }
                }
                case 'bottom-left': {
                    return {
                        from: 'bottom',
                        aligh: 'left',
                    }
                }
                case 'bottom-center': {
                    return {
                        from: 'bottom',
                        aligh: 'center',
                    }
                }
                case 'bottom-right': {
                    return {
                        from: 'bottom',
                        aligh: 'right',
                    }
                }
                default: {
                    return {
                        from: 'top',
                        aligh: 'right',
                    }
                }
            }
        }
    }
}