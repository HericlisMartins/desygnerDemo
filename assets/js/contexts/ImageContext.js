import React, { createContext, Component } from "react";

export const ImageContext = createContext();

class ImageContextProvider extends Component {
  constructor(props) {
    super(props);
    this.state = {
      imgs: [
        {
          id: "1",
          url: "https://via.placeholder.com/250",
          title: "Some Title",
          description: "Some description",
        },
        {
          id: "1",
          url: "https://via.placeholder.com/250",
          title: "Some Title",
          description: "Some description",
        },
        {
          id: "1",
          url: "https://via.placeholder.com/250",
          title: "Some Title",
          description: "Some description",
        },
        {
          id: "1",
          url: "https://via.placeholder.com/250",
          title: "Some Title",
          description: "Some description",
        },
      ],
    };
  }

  readImage() {}

  addImage() {}

  deleteImage() {}

  render() {
    return (
      <ImageContext.Provider
        value={{
          ...this.state,
          addImage: this.addImage.bind(this),
          readImage: this.readImage.bind(this),
          deleteImage: this.deleteImage.bind(this),
        }}
      >
        {this.props.children}
      </ImageContext.Provider>
    );
  }
}

export default ImageContextProvider;
