import React, { useContext } from "react";
import { ImageContext } from "../contexts/ImageContext";

import { makeStyles } from "@material-ui/core/styles";
import Card from "@material-ui/core/Card";
import CardActionArea from "@material-ui/core/CardActionArea";
import CardActions from "@material-ui/core/CardActions";
import CardContent from "@material-ui/core/CardContent";
import CardMedia from "@material-ui/core/CardMedia";
import Button from "@material-ui/core/Button";
import Typography from "@material-ui/core/Typography";

const useStyles = makeStyles({
  root: { maxWidth: 250, display:"-webkit-inline-box", margin:"0.3rem" },
  media: { height: 250 },
});

export default function ImageCards() {
  const classes = useStyles();
  const context = useContext(ImageContext);

  return context.imgs.map((img) => (
            <Card className={classes.root} variant="outlined" key={img.id}>
                <CardActionArea>
                    <CardMedia
                    className={classes.media}
                    image={img.url}
                    title="placeholder"
                    />
                    <CardContent>
                    <Typography gutterBottom variant="h5" component="h2">
                        {img.title}
                    </Typography>
                    <Typography variant="body2" color="textSecondary" component="p">
                        {img.description}
                    </Typography>
                    </CardContent>
                </CardActionArea>
                <CardActions>
                    <Button size="small" color="primary">
                    Add
                    </Button>

                    <Button size="small" color="primary">
                    Remove
                    </Button>
                </CardActions>
            </Card>
        ));
}
