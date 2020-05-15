import React from "react";

import { makeStyles } from "@material-ui/core/styles";
import Card from "@material-ui/core/Card";
import CardActionArea from "@material-ui/core/CardActionArea";
import CardActions from "@material-ui/core/CardActions";
import CardContent from "@material-ui/core/CardContent";
import CardMedia from "@material-ui/core/CardMedia";
import Button from "@material-ui/core/Button";
import Typography from "@material-ui/core/Typography";
import LibraryAddIcon from '@material-ui/icons/LibraryAdd';

const useStyles = makeStyles((theme) => ({
  root: {
    maxWidth: 250,
    minWidth: 250,
    margin: "0.5rem",
    display: "inline-block",
  },
  media: {
    height: 250,
  },
  button: {
    margin: theme.spacing(1),
  },
}));

export default function ImageCards(props) {
  const classes = useStyles();
  return (
    <Card className={classes.root} variant="outlined">
      <CardActionArea>
        <CardMedia
          className={classes.media}
          image={props.url}
          title="placeholder"
        />
        <CardContent>
          <Typography gutterBottom variant="h5" component="h2">
            {props.title}
          </Typography>
          <Typography variant="body2" color="textSecondary" component="p">
            {props.description}
          </Typography>
        </CardContent>
      </CardActionArea>
      <CardActions>
        <Button
          variant="contained"
          color="default"
          className={classes.button}
          startIcon={<LibraryAddIcon />}
        >
          Add Into Library
        </Button>
      </CardActions>
    </Card>
  );
}
