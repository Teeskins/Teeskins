import * as React from 'react';
import ReactDOM from 'react-dom';
import GridCore from './components/GridCore';
import Wireframe from './components/Wireframe';

// Interfaces
/* eslint-disable-next-line no-unused-vars */
import { IDataInterface } from './interfaces/IDataInterface';

// Services
import { URLS, UrlService } from './Services/UrlService';

require('./bootstrap');

/*
 * This global variable comes from the page associated controller
 * and contains all necessary data for its view and the wireframe
 */
declare var data: IDataInterface;

export default class Mapres extends React.Component {
  private readonly urlService: UrlService;

  public constructor (props: {}) {
    super(props);
    this.urlService = new UrlService();
  }

  public render () {
    return (
        <Wireframe totalItemsCount={data.globalData.totalItemsCount}>
          <GridCore
              userInfo={data.globalData.userInfo}
              assets={data.viewData.mapres}
              sortType={data.viewData.sortType}
              numPerRow={4}
              updateDownloads={true}
              updateLikes={true}
              page={data.viewData.page}
              idURL={this.urlService.mergeBaseWithURL(URLS.Mapres)}
              downloadsURL={this.urlService.mergeBaseWithURL(URLS.MapresDownloads)}
              likesURL={this.urlService.mergeBaseWithURL(URLS.MapresLikes)}
              fetchURL={this.urlService.mergeBaseWithURL(URLS.FetchMapres)}
              showLoadButton={false}
          />
        </Wireframe>
    );
  }
}

if (document.getElementById('app')) {
  ReactDOM.render(<Mapres />, document.getElementById('app'));
}
