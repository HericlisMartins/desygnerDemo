import React, {useContext, useState} from 'react';
import { makeStyles } from "@material-ui/core/styles";
import TextField from "@material-ui/core/TextField";
import Button from '@material-ui/core/Button';
import ImageSearchIcon from '@material-ui/icons/ImageSearch';

import {ImgurContext} from '../contexts/ImgurContext';

const useStyles = makeStyles((theme) => ({
    button: {
        margin: theme.spacing(1),
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

    return (
        <form className={classes.form} onSubmit={onCreateSubmit} noValidate autoComplete="off">
            <TextField className={classes.textField} type="text" value={addKeyword} onChange={(event) => {
                                    setKeyword(event.target.value);
                                }} label="Search Imgur.com" fullWidth={true}/>
            <Button onClick={onCreateSubmit} className={classes.button} variant="contained" color="secondary" startIcon={<ImageSearchIcon />}> Search </Button>
        </form>
    );
}
