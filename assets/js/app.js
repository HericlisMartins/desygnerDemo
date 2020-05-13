import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import ImageContextProvider from './contexts/ImageContext';
import ImageCards from './components/ImageCards';

class App extends Component {
    render() { 
        return ( 
            <ImageContextProvider>
                <ImageCards />
            </ImageContextProvider>
         );
    }
}
 
ReactDOM.render(<App />, document.getElementById('root'));