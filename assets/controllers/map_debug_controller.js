import { Controller } from '@hotwired/stimulus';

/**
 * Map Debug controller for revealing hidden map cells
 */
export default class extends Controller {
    static targets = ['container', 'button'];

    connect() {
        this.mapRevealed = false;
    }

    toggleMap() {
        if (!this.mapRevealed) {
            this.revealMap();
        } else {
            this.hideMap();
        }

        this.mapRevealed = !this.mapRevealed;
    }

    revealMap() {
        console.log("Revealing map - direct method");

        // Add a class to the main map container to enable a CSS override
        const mapElement = this.containerTarget.querySelector('.p-6.text-xs.flex.items-center.justify-center.bg-gray-800');
        if (mapElement) {
            mapElement.classList.add('debug-map-reveal');
        }


        // Add a style tag to add our CSS overrides for the debug view
        if (!document.getElementById('debug-map-styles')) {
            const styleTag = document.createElement('style');
            styleTag.id = 'debug-map-styles';
            styleTag.textContent = `
                .debug-map-reveal span.w-full:empty {
                    background-color: rgba(200, 200, 200, 0.3) !important;
                    border: 1px dashed #888 !important;
                }
                .debug-map-reveal div.w-4.h-4 {
                    opacity: 1 !important;
                }
                .debug-map-revealed {
                    position: relative;
                }
                .debug-map-revealed::after {
                    content: '?';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    color: rgba(0, 0, 0, 0.5);
                    font-size: 10px;
                }
            `;
            document.head.appendChild(styleTag);
        }

        // Apply custom styles to each empty cell (likely walls/unexplored areas)
        const emptyCells = this.containerTarget.querySelectorAll('.p-6.text-xs.flex div.w-4.h-4 span:empty');
        console.log("Found", emptyCells.length, "empty cells to modify");

        emptyCells.forEach(cell => {
            const parentCell = cell.closest('.w-4.h-4');
            if (parentCell) {
                parentCell.classList.add('debug-map-revealed');
                // We'll mark it for debug mode
                cell.setAttribute('data-debug', 'revealed');
            }
        });

        // Update button text and style
        this.buttonTarget.textContent = 'DEBUG: Hide Map';
        this.buttonTarget.classList.replace('bg-red-500', 'bg-green-500');
        this.buttonTarget.classList.replace('hover:bg-red-600', 'hover:bg-green-600');
    }

    hideMap() {
        console.log("Hiding map...");

        // Remove our debug styles
        const styleElement = document.getElementById('debug-map-styles');
        if (styleElement) {
            styleElement.remove();
        }

        // Remove the debug class from the map container
        const mapElement = this.containerTarget.querySelector('.debug-map-reveal');
        if (mapElement) {
            mapElement.classList.remove('debug-map-reveal');
        }

        // Find all revealed cells and reset them
        const revealedCells = this.containerTarget.querySelectorAll('.debug-map-revealed');
        console.log("Found", revealedCells.length, "revealed cells to hide");

        revealedCells.forEach(cell => {
            cell.classList.remove('debug-map-revealed');
        });

        // Find all cells with data-debug attribute and remove it
        this.containerTarget.querySelectorAll('[data-debug]').forEach(el => {
            el.removeAttribute('data-debug');
        });

        // Reset button text and style
        this.buttonTarget.textContent = 'DEBUG: Reveal Map';
        this.buttonTarget.classList.replace('bg-green-500', 'bg-red-500');
        this.buttonTarget.classList.replace('hover:bg-green-600', 'hover:bg-red-600');
    }
}
