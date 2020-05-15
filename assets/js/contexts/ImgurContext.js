import React, { createContext, Component } from "react";
import axios from "axios";

import ImageCards from "../components/ImageCards";
import ImgurSearch from "../components/ImgurSearch";

export const ImgurContext = createContext();

class ImgurContextProvider extends Component {
  constructor(props) {
    super(props);
    this.state = {
      imgs: [],
      dataFromLibrary: false,
    };
  }

  getImgur(keyword) {
    axios
      .get("/api/image/imgur/12/" + keyword)
      .then((response) => {
        this.setState({ imgs: response.data });
        this.setState({ dataFromLibrary: true });
      })
      .catch((error) => {
        console.error(error);
      });
    return null;
  }

  getlibrary() {
    axios
      .get("/api/image/readLibrary")
      .then((response) => {
        this.setState({ imgs: response.data });
        this.setState({ dataFromLibrary: false });
      })
      .catch((error) => {
        console.error(error);
      });
    return null;
  }

  addLibrary(imgindex) {
    axios
      .post("/api/image/InsertLibrary", this.state.imgs[imgindex])
      .then((response) => {
        console.log(response);
        if (response.data.message.level === "success") {
          alert(response.data.message.text);
        } else {
          alert(response.data.message.text);
        }
      })
      .catch((error) => {
        console.error(error);
      });
  }

  render() {
    return (
      <ImgurContext.Provider
        value={{
          ...this.state,
          getImgur: this.getImgur.bind(this),
          getlibrary: this.getlibrary.bind(this),
          addLibrary: this.addLibrary.bind(this),
        }}
      >
        <ImgurSearch />
        <div className="ImageCardsDiv">
          {this.state.imgs.map((img, i) => (
            <ImageCards
              key={"imguiCard" + i}
              title={img.title}
              description={img.description}
              url={img.url}
              addshow={this.state.dataFromLibrary}
              imgindex={i}
            />
          ))}
        </div>
        {this.props.children}
      </ImgurContext.Provider>
    );
  }
}

export default ImgurContextProvider;
