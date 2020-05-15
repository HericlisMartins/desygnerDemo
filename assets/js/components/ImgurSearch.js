import React, {useContext, useState} from 'react';
import { makeStyles } from "@material-ui/core/styles";
import TextField from "@material-ui/core/TextField";
import Button from '@material-ui/core/Button';
import ImageSearchIcon from '@material-ui/icons/ImageSearch';
import PhotoLibraryIcon from '@material-ui/icons/PhotoLibrary';
import {ImgurContext} from '../contexts/ImgurContext';

const useStyles = makeStyles((theme) => ({
    button: {
        margin: theme.spacing(1),
    },
    buttonlibrary: {
        float:"right" ,
    },
    form: {
        width: "100ch",
        margin: theme.spacing(2),
    },
    textField: {
        width: "50ch",
    }
  }));

export default function ImgurSearch() {
    const classes = useStyles();
    const context = useContext(ImgurContext);
    const [addKeyword, setKeyword] = useState('');

    const onCreateSubmit = (event) => {
        event.preventDefault();
        context.getImgur(addKeyword);
    };

    const onGetLibrary = (event) => {
        event.preventDefault();
        context.getlibrary();
    };

    return (
        <form className={classes.form} onSubmit={onCreateSubmit} noValidate autoComplete="off">
            <TextField className={classes.textField} type="text" value={addKeyword} onChange={(event) => {
                                    setKeyword(event.target.value);
                                }} label="Search Imgur.com" fullWidth={true}/>
            <Button onClick={onCreateSubmit} className={classes.button} variant="contained" color="secondary" startIcon={<ImageSearchIcon />}> Search </Button>
            <Button onClick={onGetLibrary}  className={classes.buttonlibrary} variant="contained" startIcon={<PhotoLibraryIcon />}> My Library </Button>                   
        </form>
    );
}
