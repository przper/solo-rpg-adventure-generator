import { Controller } from '@hotwired/stimulus';

/**
 * Map Tooltips controller for showing cell coordinates on hover
 */
export default class extends Controller {
    static targets = ['container'];
    
    connect() {
        console.log("Map tooltips controller connected");
        this.addCoordinateTooltips();
    }
    
    addCoordinateTooltips() {
        // Add CSS styles for tooltips
        if (!document.getElementById('map-tooltips-styles')) {
            const styleTag = document.createElement('style');
            styleTag.id = 'map-tooltips-styles';
            styleTag.textContent = `
                /* Tooltip styles */
                div.w-4.h-4 {
                    position: relative;
                }
                div.w-4.h-4:hover::after {
                    content: attr(data-coordinates);
                    position: absolute;
                    bottom: calc(100% + 5px);
                    left: 50%;
                    transform: translateX(-50%);
                    background-color: rgba(0, 0, 0, 0.8);
                    color: white;
                    padding: 2px 4px;
                    border-radius: 3px;
                    font-size: 10px;
                    white-space: nowrap;
                    z-index: 100;
                    pointer-events: none;
                }
                div.w-4.h-4:hover::before {
                    content: "";
                    position: absolute;
                    top: -5px;
                    left: 50%;
                    transform: translateX(-50%);
                    border-width: 5px 5px 0;
                    border-style: solid;
                    border-color: rgba(0, 0, 0, 0.8) transparent transparent;
                    z-index: 100;
                    pointer-events: none;
                }
                div.w-4.h-4:hover {
                    z-index: 10;
                    outline: 1px solid rgba(255, 255, 255, 0.5);
                }
            `;
            document.head.appendChild(styleTag);
        }
        
        // Process all cells in the map to add coordinate tooltips
        const gridCells = this.containerTarget.querySelectorAll('div.w-4.h-4');
        let x = 0, y = 0;
        
        // Count columns in the first row to determine grid structure
        const firstRowCells = this.containerTarget.querySelectorAll('.p-6.text-xs.flex > div:first-child > div');
        const gridWidth = firstRowCells.length;
        
        gridCells.forEach((cell, index) => {
            // Calculate x,y coordinates based on position in the grid
            x = index % gridWidth;
            y = Math.floor(index / gridWidth);
            
            // Get cell type (Room, Corridor, Wall)
            let cellType = 'Wall';
            if (cell.querySelector('span:not(:empty)')) {
                const content = cell.textContent.trim();
                cellType = content === 'R' ? 'Room' : (content === 'C' ? 'Corridor' : 'Wall');
            }
            
            // In the rendered grid, columns are actually y-axis and rows are x-axis
            // So we need to show coordinates to match how movement works
            cell.setAttribute('data-coordinates', `${cellType} [${x},${y}]`);
        });
    }
}