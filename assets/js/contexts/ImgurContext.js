import React, { createContext, Component } from "react";
import axios from 'axios';

import ImageCards from "../components/ImageCards";
import ImgurSearch from "../components/ImgurSearch";

export const ImgurContext = createContext();

class ImgurContextProvider extends Component {
  constructor(props) {
    super(props);
    this.state = {
      imgs: [],
      noresults:0
    };
  }

  getImgur(keyword) {
    axios
      .get("/api/image/imgur/12/"+keyword)
      .then((response) => {
        this.setState({
          imgs: response.data,
        });
      })
      .catch((error) => {
        console.error(error);
      });
      return null;
  }

  addImage() {}

  deleteImage() {}

  render() {
    
    return (
      <ImgurContext.Provider
        value={{
          ...this.state,
          getImgur: this.getImgur.bind(this),
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
            />
          ))}
        </div>
        {this.props.children}
      </ImgurContext.Provider>
    );
  }
}

export default ImgurContextProvider;
