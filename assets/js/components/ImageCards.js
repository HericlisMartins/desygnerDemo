import React, {useContext} from 'react'
import {ImageContext} from '../contexts/ImageContext'

export default function ImageCards() {
    const context = useContext(ImageContext);
    return (
        <div>
            {
            context.imgFiles.map(img => (
                <div key={img.id}>
                    {img.url}
                </div>
            ))
            }
        </div>
    )
}
