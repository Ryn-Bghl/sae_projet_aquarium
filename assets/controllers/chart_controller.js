import { Controller } from "@hotwired/stimulus";
import * as d3 from "d3";

export default class extends Controller {
    static values = {
        data: Array,
        label: String,
        color: String,
        thresholds: Object,
    };

    static targets = ["container"];

    connect() {
        console.log("Chart controller connected");
        if (this.hasDataValue) {
            this.renderChart();
            
            // Add resize observer to make it responsive
            this.resizeObserver = new ResizeObserver(() => {
                this.renderChart();
            });
            this.resizeObserver.observe(this.containerTarget);
        }
    }

    disconnect() {
        if (this.resizeObserver) {
            this.resizeObserver.disconnect();
        }
    }

    renderChart() {
        const data = this.dataValue;
        const container = this.containerTarget;

        // Ensure container can host absolute tooltip
        container.style.position = "relative";

        // Clear previous chart
        container.innerHTML = "";
        
        // Tooltip Element
        const tooltip = d3.select(container)
            .append("div")
            .attr("class", "chart-tooltip")
            .style("opacity", 0);

        // Dimensions
        const margin = { top: 20, right: 30, bottom: 30, left: 40 };
        // Use container dimensions
        const containerWidth = container.clientWidth || 600;
        const containerHeight = container.clientHeight || 300;
        
        const width = containerWidth - margin.left - margin.right;
        const height = containerHeight - margin.top - margin.bottom;

        // Prevent negative dimensions if container is hidden/collapsed
        if (width <= 0 || height <= 0) return;

        // SVG
        const svg = d3
            .select(container)
            .append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", `translate(${margin.left},${margin.top})`);

        // Scales
        // Ensure data is sorted by date for bisector to work
        data.sort((a, b) => new Date(a.date) - new Date(b.date));

        const x = d3
            .scaleTime()
            .domain(d3.extent(data, (d) => new Date(d.date)))
            .range([0, width]);

        const yDomain = d3.extent(data, (d) => d.value);
        if (this.hasThresholdsValue) {
            const { min, max } = this.thresholdsValue;
            // Expand domain to include thresholds with a bit of padding
            yDomain[0] = Math.min(yDomain[0], min);
            yDomain[1] = Math.max(yDomain[1], max);
            
            // Add 10% padding
            const padding = (yDomain[1] - yDomain[0]) * 0.1 || 1;
            yDomain[0] -= padding;
            yDomain[1] += padding;
        }

        const y = d3
            .scaleLinear()
            .domain(yDomain)
            .nice()
            .range([height, 0]);

        // Line
        const line = d3
            .line()
            .x((d) => x(new Date(d.date)))
            .y((d) => y(d.value))
            .curve(d3.curveMonotoneX);

        // Add X Axis
        svg.append("g")
            .attr("transform", `translate(0,${height})`)
            .call(
                d3.axisBottom(x)
                    .tickValues(data.map(d => new Date(d.date))) // Show a tick for every data point
                    .tickFormat(d3.timeFormat("%d/%m"))
            )
            .style("color", "#a0a0a0")
            .selectAll("text")
                .style("text-anchor", "end")
                .attr("dx", "-.8em")
                .attr("dy", ".15em")
                .attr("transform", "rotate(-45)");

        // Add Y Axis
        svg.append("g").call(d3.axisLeft(y)).style("color", "#a0a0a0");

        // Define stroke color and potential gradient
        let strokeColor = this.colorValue || "#00bcd4";
        if (this.hasThresholdsValue) {
            const { min, max } = this.thresholdsValue;
            const gradientId = `line-gradient-${Math.random().toString(36).substr(2, 9)}`;
            
            const defs = svg.append("defs");
            const gradient = defs.append("linearGradient")
                .attr("id", gradientId)
                .attr("gradientUnits", "userSpaceOnUse")
                .attr("x1", 0)
                .attr("y1", height)
                .attr("x2", 0)
                .attr("y2", 0);

            // Calculate offsets (0 is bottom, 1 is top)
            // y(min) and y(max) are pixel values from top
            const offsetMin = Math.max(0, Math.min(1, (height - y(min)) / height));
            const offsetMax = Math.max(0, Math.min(1, (height - y(max)) / height));

            gradient.append("stop").attr("offset", 0).attr("stop-color", "#ef5350"); // Red (too low)
            gradient.append("stop").attr("offset", offsetMin).attr("stop-color", "#ef5350");
            gradient.append("stop").attr("offset", offsetMin).attr("stop-color", "#66bb6a"); // Green (optimal)
            gradient.append("stop").attr("offset", offsetMax).attr("stop-color", "#66bb6a");
            gradient.append("stop").attr("offset", offsetMax).attr("stop-color", "#ef5350"); // Red (too high)
            gradient.append("stop").attr("offset", 1).attr("stop-color", "#ef5350");

            strokeColor = `url(#${gradientId})`;
        }

        // Add Path
        svg.append("path")
            .datum(data)
            .attr("fill", "none")
            .attr("stroke", strokeColor)
            .attr("stroke-width", 2)
            .attr("d", line);

        // Add dots
        svg.selectAll(".dot")
            .data(data)
            .enter()
            .append("circle")
            .attr("class", "dot")
            .attr("cx", (d) => x(new Date(d.date)))
            .attr("cy", (d) => y(d.value))
            .attr("r", 4)
            .attr("fill", (d) => {
                if (this.hasThresholdsValue) {
                    const { min, max } = this.thresholdsValue;
                    return (d.value >= min && d.value <= max) ? "#66bb6a" : "#ef5350";
                }
                return this.colorValue || "#00bcd4";
            });

        // --- Tooltip Logic ---
        
        // Focus Circle (the highlighted point)
        const focus = svg.append("g")
            .append("circle")
            .style("fill", this.colorValue || "#00bcd4")
            .attr("stroke", "white")
            .attr("stroke-width", 2)
            .attr("r", 6)
            .style("opacity", 0);

        // Overlay for catching mouse events
        svg.append("rect")
            .attr("width", width)
            .attr("height", height)
            .style("fill", "none")
            .style("pointer-events", "all")
            .on("pointerenter", () => {
                focus.style("opacity", 1);
                tooltip.style("opacity", 1);
            })
            .on("pointerleave", () => {
                focus.style("opacity", 0);
                tooltip.style("opacity", 0);
            })
            .on("pointermove", (event) => {
                // Find nearest data point
                const bisect = d3.bisector((d) => new Date(d.date)).left;
                const [mX, mY] = d3.pointer(event);
                const x0 = x.invert(mX);
                const i = bisect(data, x0, 1);
                const d0 = data[i - 1];
                const d1 = data[i];
                // Handle edge cases where i might be out of bounds
                let d = d0; 
                if (d1 && d0) {
                    d = x0 - new Date(d0.date) > new Date(d1.date) - x0 ? d1 : d0;
                } else if (d1) {
                    d = d1;
                }
                
                if (!d) return;

                // Update focus position (stays on the line)
                focus
                    .attr("cx", x(new Date(d.date)))
                    .attr("cy", y(d.value));

                if (this.hasThresholdsValue) {
                    const { min, max } = this.thresholdsValue;
                    focus.style("fill", (d.value >= min && d.value <= max) ? "#66bb6a" : "#ef5350");
                }

                // Position tooltip exactly under the mouse
                const [contX, contY] = d3.pointer(event, container);
                const formatDate = d3.timeFormat("%d/%m/%Y");
                tooltip
                    .html(`<strong>${formatDate(new Date(d.date))}</strong><br>Val: ${d.value}`)
                    .style("left", contX + "px")
                    .style("top", (contY + 20) + "px");
            });
    }
}
