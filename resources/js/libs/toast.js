class Toast {
    constructor() {
        this.toasts = [];
        this.container = null;
        this.init();
    }

    init() {
        if (!document.querySelector(".toast-container")) {
            this.container = document.createElement("div");
            this.container.className = "toast-container fixed top-5 right-5 flex flex-col gap-4 z-50";
            document.body.appendChild(this.container);
        }
        this.container = document.querySelector(".toast-container");
    }

    create({ message, type = "info", duration = 3000, dismissible = true, position = "top-right" }) {
        const id = Date.now();

        this.container.className = `toast-container fixed flex flex-col gap-4 z-50 ${this.getPositionClasses(position)}`;

        const toast = document.createElement("div");
        toast.className = `toast max-w-xs bg-white border rounded-lg shadow-lg flex p-4 items-center gap-4 transition transform opacity-0 translate-y-2`;
        toast.setAttribute("data-id", id);

        const icon = document.createElement("i");
        icon.className = `fa-solid ${this.getIconClass(type)} text-${this.getColor(type)}-600 text-md`;
        toast.appendChild(icon);

        const messageEl = document.createElement("span");
        messageEl.className = "text-md text-gray-700";
        messageEl.textContent = message;
        toast.appendChild(messageEl);

        if (dismissible) {
            const dismissBtn = document.createElement("button");
            dismissBtn.className = "ml-auto text-md hover:bg-gray-50 px-2 py-1 rounded-lg text-gray-600 hover:text-gray-900";
            dismissBtn.innerHTML = "&times;";
            dismissBtn.onclick = () => this.remove(id);
            toast.appendChild(dismissBtn);
        }

        this.container.appendChild(toast);
        this.toasts.push(id);

        requestAnimationFrame(() => {
            toast.classList.add("opacity-100", "translate-y-0");
        });

        if (duration > 0) {
            setTimeout(() => this.remove(id), duration);
        }
    }

    remove(id) {
        const toast = this.container.querySelector(`[data-id="${id}"]`);
        if (toast) {
            toast.classList.remove("opacity-100", "translate-y-0");
            toast.classList.add("opacity-0", "translate-y-2");

            toast.addEventListener("transitionend", () => {
                toast.remove();
                this.toasts = this.toasts.filter((toastId) => toastId !== id);
            });
        }
    }

    getIconClass(type) {
        switch (type) {
            case "success":
                return "fa-check-circle";
            case "error":
                return "fa-times-circle";
            case "warning":
                return "fa-exclamation-circle";
            case "info":
            default:
                return "fa-info-circle";
        }
    }

    getColor(type) {
        switch (type) {
            case "success":
                return "green";
            case "error":
                return "red";
            case "warning":
                return "yellow";
            case "info":
            default:
                return "blue";
        }
    }

    getPositionClasses(position) {
        switch (position) {
            case "top-right":
                return "top-5 right-5";
            case "top-left":
                return "top-5 left-5";
            case "bottom-right":
                return "bottom-5 right-5";
            case "bottom-left":
                return "bottom-5 left-5";
            default:
                return "top-5 right-5";
        }
    }
}

export default Toast;
