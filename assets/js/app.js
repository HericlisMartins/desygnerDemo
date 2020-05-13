import React, { Component } from "react";
import ReactDOM from "react-dom";
import ImageContextProvider from "./contexts/ImageContext";
import ImageCards from "./components/ImageCards";

import CssBaseline from "@material-ui/core/CssBaseline";
import Container from "@material-ui/core/Container";

class App extends Component {
  render() {
    return (
      <ImageContextProvider>
        <ImageCards />
      </ImageContextProvider>
    );
  }
}

ReactDOM.render(<App />, document.getElementById("root"));
