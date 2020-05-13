import React, { createContext, Component } from 'react';

export const ImageContext = createContext();

class ImageContextProvider extends Component {
    constructor(props) {
        super(props);
        this.state = {
            imgFiles: [ 
                {id: "1",
                url: "google.com"},
            ],
        };
    }

    readImage(){

    }

    createImage(){

    }

    deleteImage(){

    }

    render() { 
        return (  
            <ImageContext.Provider value={{
                ...this.state,
                createImage: this.createImage.bind(this),
                readImage: this.readImage.bind(this),
                deleteImage: this.deleteImage.bind(this),
            }}>
                {this.props.children}
            </ImageContext.Provider>
        );
    }
}

export default ImageContextProvider;