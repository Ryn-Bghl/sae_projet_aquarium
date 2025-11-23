// import "./stimulus_bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/global.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");

import React from "react";
import ReactDOM from "react-dom/client";
import App from "./react/controllers/App.jsx";

const root = ReactDOM.createRoot(document.getElementById("root"));
root.render(<App />);
