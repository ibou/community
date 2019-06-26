import React, {Component} from 'react';
import {Card, CardHeader, CardText, CardTitle} from "material-ui/Card";
 
const ItemCard = ({ card, style, children }) => (
    <Card style={style}>
        <CardHeader title={card.author} avatar={card.avatarUrl} />
        <CardTitle title={card.title} subtitle={card.subtitle} />
        <CardText>{children} </CardText>
        
    </Card>
);

export default ItemCard;
 
