import { Controller } from "@hotwired/stimulus";
import * as d3 from "d3";

export default class extends Controller {
    static values = {
        data: Array,
        label: String,
        color: String,
    };

    static targets = ["container"];

    connect() {
        console.log("Chart controller connected");
        if (this.hasDataValue) {
            this.renderChart();
        }
    }

    renderChart() {
        const data = this.dataValue;
        const container = this.containerTarget;

        // Clear previous chart
        container.innerHTML = "";

        // Dimensions
        const margin = { top: 20, right: 30, bottom: 30, left: 40 };
        const width = container.clientWidth - margin.left - margin.right;
        const height = 300 - margin.top - margin.bottom;

        // SVG
        const svg = d3
            .select(container)
            .append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", `translate(${margin.left},${margin.top})`);

        // Scales
        // Assuming data is array of objects { date: "...", value: ... }
        // Parse dates if necessary, currently assuming ISO strings or timestamps that D3 can handle or we pre-process
        const parseDate = d3.timeParse("%Y-%m-%d %H:%M:%S"); // Adapt to your format

        const x = d3
            .scaleTime()
            .domain(d3.extent(data, (d) => new Date(d.date)))
            .range([0, width]);

        const y = d3
            .scaleLinear()
            .domain([0, d3.max(data, (d) => d.value)])
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
            .call(d3.axisBottom(x).ticks(5))
            .style("color", "#a0a0a0");

        // Add Y Axis
        svg.append("g").call(d3.axisLeft(y)).style("color", "#a0a0a0");

        // Add Path
        svg.append("path")
            .datum(data)
            .attr("fill", "none")
            .attr("stroke", this.colorValue || "#00bcd4")
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
            .attr("fill", this.colorValue || "#00bcd4");
    }
}
